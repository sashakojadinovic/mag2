<?php
/**
 * Copyright © 2022 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_Verspaning
 */
namespace Trienekens\Verspaning\Setup\Patch\Data;

use Magento\Eav\Api\AttributeGroupRepositoryInterface;
use Magento\Eav\Api\Data\AttributeGroupInterfaceFactory;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddVerspaningAttributes implements DataPatchInterface
{
    private $_moduleDataSetup;

    private $_eavSetupFactory;

    private $attributeGroupFactory;

    private $attributeGroupRepository;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        AttributeGroupInterfaceFactory $attributeGroupFactory,
        AttributeGroupRepositoryInterface $attributeGroupRepository
    ) {
        $this->_moduleDataSetup = $moduleDataSetup;
        $this->_eavSetupFactory = $eavSetupFactory;
        $this->attributeGroupFactory = $attributeGroupFactory;
        $this->attributeGroupRepository = $attributeGroupRepository;
    }

    public function apply()
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->_eavSetupFactory->create(['setup' => $this->_moduleDataSetup]);
        $attributeGroup = 'Verspaning';

        $attributeCodes = [
            'product_aansnijlengte' => 'Aansnijlengte',
            'product_aantal' => 'Aantal',
            'product_aantal_boren' => 'Aantal boren',
            'product_aantal_centreerboren' => 'Aantal centreerboren',
            'product_aantal_groeven' => 'Aantal groeven',
            'product_aantal_messen' => 'Aantal messen',
            'product_aantal_nldeelcirkel' => 'Aantal NL/Ø/deelcirkel',
            'product_aantal_tanden' => 'Aantal tanden',
            'product_aantal_tanden_bw' => 'Aantal tanden BW',
            'product_aantal_tanden_hz' => 'Aantal tanden HZ',
            'product_aantal_tanden_per_inch' => 'Aantal tanden per inch',
            'product_afmeting' => 'Afmeting',
            'product_afmetingen_bxhxd_mm' => 'Afmetingen (BxHxD) mm',
            'product_afwerking' => 'Afwerking',
            'product_alpha2' => 'alpha/2',
            'product_aluminium__8_si' => 'Aluminium < 8% Si',
            'product_aluminium__8_si_f' => 'Aluminium < 8% Si f',
            'product_aluminium__8_si_fz' => 'Aluminium < 8% Si fz',
            'product_artikel' => 'Artikel',
            'product_asgat' => 'Asgat-Ø',
            'product_boor' => 'Boor-Ø',
            'product_boor_d1' => 'Boor-Ø d1',
            'product_boor_d1_k12' => 'Boor-Ø d1 k12',
            'product_boor_d1_x_verzinkboor_d2' => 'Boor-Ø d1 x verzinkboor-Ø d2',
            'product_boorbereik' => 'Boorbereik',
            'product_boorgat' => 'Boorgat',
            'product_boorpasta' => 'Boorpasta',
            'product_boortraplengte_l3' => 'Boortraplengte l3',
            'product_boortrappen' => 'Boortrappen-Ø',
            'product_boring_d1' => 'Boring d1',
            'product_boring_d2__h_7' => 'Boring d2 = H 7',
            'product_boring_f7' => 'Boring F7',
            'product_boring_h7' => 'Boring H7',
            'product_boring' => 'Boring-Ø',
            'product_breedte' => 'Breedte',
            'product_breedte_b' => 'Breedte b',
            'product_breedte_j11' => 'Breedte j11',
            'product_buiten_x_hoogte' => 'Buiten-Ø x hoogte',
            'product_d__voor_schroefdraad' => 'd ≥ voor schroefdraad-Ø',
            'product_d1' => 'd1',
            'product_d2' => 'D2',
            'product_d3' => 'D3',
            'product_diameter_n6' => 'Diameter n6',
            'product_dikte' => 'Dikte',
            'product_din_2184' => 'DIN 2184',
            'product_din_352' => 'DIN 352',
            'product_din_5157' => 'DIN 5157',
            'product_dk' => 'dk',
            'product_draad' => 'Draad-Ø',
            'product_draad_x_spoed' => 'Draad x spoed',
            'product_ds' => 'ds',
            'product_frees' => 'Frees-Ø',
            'product_gangen' => 'Gangen',
            'product_gangen_per_inch' => 'Gangen per inch',
            'product_gehard_staal__55_hrc' => 'Gehard staal < 55 HRC',
            'product_gehard_staal__55_hrc_f' => 'Gehard staal < 55 HRC f',
            'product_gehard_staal__55_hrc_fz' => 'Gehard staal < 55 HRC fz',
            'product_gehard_staal__60_hrc' => 'Gehard staal < 60 HRC',
            'product_gehard_staal__60_hrc_f' => 'Gehard staal > 60 HRC f',
            'product_gehard_staal__60_hrc_fz' => 'Gehard staal < 60 HRC fz',
            'product_gietijzer_gggts' => 'Gietijzer GG/GTS',
            'product_gietijzer_gggts_f' => 'Gietijzer GG/GTS f',
            'product_gietijzer_ggg' => 'Gietijzer GGG',
            'product_grafiet_gfkcfkduropl' => 'Grafiet GFK/CFK/Duropl.',
            'product_groeflengte_l3' => 'Groeflengte l3',
            'product_grootste__d1' => 'Grootste Ø d1',
            'product_grootte' => 'Grootte',
            'product_hals' => 'Hals-Ø',
            'product_hals_ca' => 'Hals-Ø ca.',
            'product_hals_d3' => 'Hals-Ø d3',
            'product_hoek_alpha' => 'Hoek alpha',
            'product_hoekafschuining' => 'Hoekafschuining',
            'product_hoekafschuining_45' => 'Hoekafschuining 45°',
            'product_hoekradius' => 'Hoekradius',
            'product_hoekradius_r' => 'Hoekradius r',
            'product_hoekradius_r__001' => 'Hoekradius r ± 0,01',
            'product_hoekradius_r__002' => 'Hoekradius r ± 0,02',
            'product_inhoud' => 'Inhoud',
            'product_inhoud_van_de_set' => 'Inhoud van de set:',
            'product_inhoud_van_de_set' => 'Inhoud van de set-Ø',
            'product_instelbereik_' => 'Instelbereik Ø',
            'product_kerngat' => 'Kerngat-Ø',
            'product_kleine_' => 'Kleine Ø',
            'product_kleinergroter_' => 'Kleiner/groter Ø',
            'product_kleinste_' => 'Kleinste Ø',
            'product_kleinste__d3' => 'Kleinste Ø d3',
            'product_kleinste__d3__js14' => 'Kleinste Ø d3 = js14',
            'product_kleinste__voorgeboord_gat' => 'Kleinste Ø voorgeboord gat',
            'product_kop' => 'Kop-Ø',
            'product_kop_d2' => 'Kop-Ø d2',
            'product_koper_culeg' => 'Koper Cu-leg.',
            'product_koper_culeg_f' => 'Koper Cu-leg. f',
            'product_koplengte' => 'Koplengte',
            'product_l1' => 'l1',
            'product_l2' => 'L2',
            'product_l3' => 'l3',
            'product_l6' => 'l6',
            'product_lengte' => 'Lengte',
            'product_lengte_l3' => 'Lengte l3',
            'product_meslengte' => 'Meslengte',
            'product_mesnummer' => 'Mesnummer',
            'product_mk' => 'MK',
            'product_nominale_' => 'Nominale Ø',
            'product_nominale__d1' => 'Nominale Ø d1',
            'product_nominale__k11' => 'Nominale Ø k11',
            'product_norm' => 'Norm',
            'product_' => 'Ø',
            'product__d1' => 'Ø d1',
            'product__d1__00012' => 'Ø d1 = 0/–0,012',
            'product__d1__0002' => 'Ø d1 = 0/–0,02',
            'product__d1__0_003' => 'Ø d1 = 0/ 0,03',
            'product__d1__e8' => 'Ø d1 = e8',
            'product__d1__f9' => 'Ø d1 = f9',
            'product__d1_h10' => 'Ø d1 h10',
            'product__d1__h11' => 'Ø d1 = h11',
            'product__d1__h5' => 'Ø d1 = h5',
            'product__d1__h8' => 'Ø d1 = h8',
            'product__d1__h9' => 'Ø d1 = h9',
            'product__d1__j14' => 'Ø d1 = j14',
            'product__d1__js12' => 'Ø d1 = js12',
            'product__d1__js16' => 'Ø d1 = js16',
            'product__d1__k10' => 'Ø d1 = k10',
            'product__d1__k12' => 'Ø d1 = k12',
            'product__d1__m7' => 'Ø d1 = m7',
            'product__h6' => 'Ø h6',
            'product__h7' => 'Ø h7',
            'product__h8' => 'Ø h8',
            'product__h8_x_totale_lengte' => 'Ø h8 x totale lengte',
            'product__j' => 'Ø J',
            'product___js9' => 'Ø = js9',
            'product___m7' => 'Ø = m7',
            'product__m7h7' => 'Ø m7/h7',
            'product__x_breedte_d1_x_b1' => 'Ø x breedte d1 x b1',
            'product__x_breedte_x_boring' => 'Ø x breedte x boring',
            'product__x_breite_d1__d11_x_b__d11' => 'Ø x Breite d1 = d11 x b = d11',
            'product__x_breite_d1__h12_x_b__e8' => 'Ø x Breite d1 = h12 x b = e8',
            'product__x_dikte_x_boring' => 'Ø x dikte x boring',
            'product_d1__m7' => 'Ød1 = m7',
            'product_oplopend' => 'Oplopend',
            'product_r_2' => 'R 2',
            'product_r__h11' => 'r = H11',
            'product_r1' => 'R1',
            'product_r3' => 'R3',
            'product_radius_r' => 'Radius r',
            'product_radius_r__002' => 'Radius r ± 0,02',
            'product_radius_r_0015' => 'Radius r ±0,015',
            'product_radius_r__h11' => 'Radius r = H11',
            'product_radius_rh_11' => 'Radius rH 11',
            'product_rij' => 'Rij',
            'product_rvs_austenitisch' => 'RVS austenitisch',
            'product_rvs_duplex' => 'RVS duplex',
            'product_rvs_ferritischmartensitisch' => 'RVS ferritisch/martensitisch',
            'product_schacht' => 'Schacht',
            'product_schacht_mc' => 'Schacht MC',
            'product_schacht' => 'Schacht-Ø',
            'product_schacht_d2' => 'Schacht-Ø d2',
            'product_schacht_d2__h5' => 'Schacht-Ø d2 = h5',
            'product_schacht_d2__h6' => 'Schacht-Ø d2 = h6',
            'product_schacht_d2_h8' => 'Schacht-Ø d2 h8',
            'product_schacht_d2_h9' => 'Schacht-Ø d2 h9',
            'product_schacht__h6' => 'Schacht-Ø = h6',
            'product_schacht__h8' => 'Schacht Ø h8',
            'product_schacht__h9' => 'Schacht Ø h9',
            'product_schachtvierkant' => 'Schacht-vierkant',
            'product_schachtlengte' => 'Schachtlengte',
            'product_schachtlengte_l3' => 'Schachtlengte l3',
            'product_schachttype' => 'Schachttype',
            'product_schroefdraad' => 'Schroefdraad',
            'product_schroefdraad_bsw' => 'Schroefdraad BSW',
            'product_schroefdraad_inch' => 'Schroefdraad inch',
            'product_schroefdraad_metrische' => 'Schroefdraad metrische',
            'product_schroefdraad_uncunf' => 'Schroefdraad UNC/UNF',
            'product_schroefdraadlengte' => 'Schroefdraadlengte',
            'product_setinhoud' => 'Setinhoud',
            'product_setinhoud__h7' => 'Setinhoud Ø h7',
            'product_setinhoud__h8' => 'Setinhoud Ø h8',
            'product_sleutelwijdte' => 'Sleutelwijdte',
            'product_snijbereik' => 'Snijbereik',
            'product_snijdiepte' => 'Snijdiepte',
            'product_snijhoogte_a_45_b__js_14' => 'Snijhoogte a 45° b = js 14',
            'product_snijhoogte_a_60_b__js_14' => 'Snijhoogte a 60° b = js 14',
            'product_snijkantlengte' => 'Snijkantlengte',
            'product_snijkantlengte_i2' => 'Snijkantlengte I2',
            'product_snijkantlengte_i4' => 'Snijkantlengte I4',
            'product_snijkantlengte_l1' => 'Snijkantlengte l1',
            'product_snijkantlengte_l2' => 'Snijkantlengte L2',
            'product_snijsnelheid' => 'Snijsnelheid',
            'product_snijvlaklengte' => 'Snijvlaklengte',
            'product_spaanhoek' => 'Spaanhoek',
            'product_spangat_mm_type_centrisch' => 'Spangat-Ø mm type centrisch',
            'product_spanwijdte_vierkant' => 'Spanwijdte vierkant',
            'product_spiraallengte' => 'Spiraallengte',
            'product_spiraallengte_l' => 'Spiraallengte l',
            'product_spiraallengte_l2' => 'Spiraallengte l2',
            'product_spoed' => 'Spoed',
            'product_staal__1000_nmm' => 'Staal < 1.000 N/mm²',
            'product_staal__1000_nmm_f' => 'Staal < 1.000 N/mm² f',
            'product_staal__1000_nmm_fz' => 'Staal < 1.000 N/mm² fz',
            'product_staal__1400_nmm' => 'Staal < 1.400 N/mm²',
            'product_staal__1400_nmm_f' => 'Staal < 1.400 N/mm² f',
            'product_staal__1400_nmm_fz' => 'Staal < 1.400 N/mm² fz',
            'product_staal__1000_nmm' => 'Staal < 1000 N/mm²',
            'product_staal__1000_nmm_f' => 'Staal < 1000 N/mm² f',
            'product_staal__1000_nmm_fz' => 'Staal < 1000 N/mm² fz',
            'product_staal__1400_nmm' => 'Staal < 1400 N/mm²',
            'product_staal__1400_nmm_f' => 'Staal < 1400 N/mm² f',
            'product_staal__700_nmm' => 'Staal < 700 N/mm²',
            'product_staal__700_nmm_f' => 'Staal < 700 N/mm² f',
            'product_staal__700_nmm_fz' => 'Staal < 700 N/mm² fz',
            'product_straal' => 'Straal',
            'product_sw_x_hoogte' => 'SW x hoogte',
            'product_tanden_en_tandvorm' => 'Tanden en tandvorm',
            'product_tandsteek_t' => 'Tandsteek T',
            'product_tap' => 'Tap-Ø',
            'product_tap_e8_d' => 'Tap-Ø (e8) D',
            'product_tekstuitbreiding_lang' => 'Tekstuitbreiding lang',
            'product_titanium__850_nmm' => 'Titanium > 850 N/mm²',
            'product_titanium__850_nmm_f' => 'Titanium > 850 N/mm² f',
            'product_toerental' => 'Toerental',
            'product_tolerantie' => 'Tolerantie',
            'product_totale_lengte' => 'Totale lengte',
            'product_totale_lengte_incl_wp_il1' => 'Totale lengte incl. WP Il1',
            'product_totale_lengte_l1' => 'Totale lengte l1',
            'product_totale_lengte_l2' => 'Totale lengte l2',
            'product_totale_lengte_l3' => 'Totale lengte L3',
            'product_traplengte' => 'Traplengte',
            'product_to_type' => 'Type',
            'product_uitvoering' => 'Uitvoering',
            'product_verzink_k8_d' => 'Verzink-Ø (K8) D',
            'product_verzinkbereik' => 'Verzinkbereik',
            'product_verzinkboor' => 'Verzinkboor-Ø',
            'product_vierkant' => 'Vierkant',
            'product_vierkant_sw' => 'Vierkant SW',
            'product_voor_aantal_boren' => 'Voor aantal boren',
            'product_voor_boor' => 'Voor boor-Ø',
            'product_voor_din_219' => 'Voor DIN 219',
            'product_voor_draadtappen_din_352' => 'Voor draadtappen DIN 352',
            'product_voor_draadtappen_din_5157' => 'Voor draadtappen DIN 5157',
            'product_voor_gbschacht' => 'Voor GB-schacht-Ø',
            'product_voor_gbvierkant' => 'Voor GB-vierkant',
            'product_voor_messen' => 'Voor messen',
            'product_voor_schroefdraad' => 'voor schroefdraad',
            'product_voor_snijijzer_metrisch' => 'voor snij-ijzer metrisch',
            'product_voor_snijijzer_metrisch_fijn' => 'voor snij-ijzer metrisch fijn',
            'product_voor_snijijzer_x_hoogte' => 'voor snij-ijzer-Ø x hoogte',
            'product_voor_snijijzer_unc' => 'voor snij-ijzer UNC',
            'product_voor_snijijzer_unf' => 'voor snij-ijzer UNF',
            'product_voor_snijijzer_whitworth' => 'voor snij-ijzer Whitworth',
            'product_voor_snijdiepte' => 'Voor snijdiepte',
            'product_voor_spanwijdte_vierkant' => 'Voor spanwijdte vierkant',
            'product_voor_spien' => 'Voor spieën',
            'product_voor_tgroeven_din_650' => 'Voor T-groeven DIN 650',
            'product_voor_tapschachten_volgens_din' => 'Voor tapschachten volgens DIN',
            'product_voor_tapschachten_volgens_iso' => 'Voor tapschachten volgens ISO',
            'product_voor_wanddiktes' => 'voor wanddiktes',
            'product_voor_werkstuk' => 'Voor werkstuk-Ø',
            'product_voor_zaagblad' => 'Voor zaagblad-Ø',
            'product_vrijgeslepen_hals_l3' => 'Vrijgeslepen hals l3',
            'product_vrijgeslepen_hals_l4' => 'Vrijgeslepen hals l4',
            'product_wgnr' => 'WGNr.',
            'product_zeskant' => 'Zeskant'
        ];

        foreach ($attributeCodes as $attributeCode => $attributeLabel) {

            // create attribute
            $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, $attributeCode, [
                'group'              => $attributeGroup,
                'input'              => 'select',
                'type'               => 'int',
                'label'              => $attributeLabel,
                'visible'            => true,
                'required'           => false,
                'user_defined'               => true,
                'searchable'                 => false,
                'filterable'                 => false,
                'comparable'                 => false,
                'visible_on_front'           => true,
                'visible_in_advanced_search' => false,
                'is_html_allowed_on_front'   => true,
                'used_for_promo_rules'       => false,
                'source'                     => null,
                'frontend_class'             => '',
                'global'                     =>  \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'unique'                     => false,
                'apply_to'                   => 'simple,grouped,configurable,downloadable,virtual,bundle'
            ]);

            // assign attribute to set and group
            $attributeGroupName = 'Verspaning';
            $entityTypeId = $eavSetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
            $allAttributeSetIds = $eavSetup->getAllAttributeSetIds($entityTypeId);

            foreach ($allAttributeSetIds as $attributeSetId) {
                $groupId = $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, $attributeGroupName);

                // If our attribute group name does not exist, we create it
                if ($groupId == $eavSetup->getDefaultAttributeGroupId(\Magento\Catalog\Model\Product::ENTITY)) {
                    $this->createAttributeGroup($attributeGroupName, $attributeSetId);
                    $groupId = $eavSetup->getAttributeGroupId(\Magento\Catalog\Model\Product::ENTITY, $attributeSetId, $attributeGroupName);
                }

                $eavSetup->addAttributeToGroup(
                    $entityTypeId,
                    $attributeSetId,
                    $groupId,
                    $attributeCode,
                    30
                );
            }
        }
    }

    /**
     * @param $attributeGroupName
     * @param $attributeSetId
     */
    private function createAttributeGroup($attributeGroupName, $attributeSetId)
    {
        $attributeGroup = $this->attributeGroupFactory->create();

        $attributeGroup->setAttributeSetId($attributeSetId);
        $attributeGroup->setAttributeGroupName($attributeGroupName);
        try {
            $this->attributeGroupRepository->save($attributeGroup);
        } catch (NoSuchEntityException $e) {
        } catch (StateException $e) {
        }
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

    public static function getVersion()
    {
        return '1.0.0';
    }
}