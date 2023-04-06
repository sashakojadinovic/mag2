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

class AddMeettechniekAttributes implements DataPatchInterface
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
        $attributeGroup = 'Meettechniek';

        /**
         * TODO: CSV header columns
         * product_1_wijzeromwenteling,product_a,product_aanslaglengte,product_aantal,product_aantal_bladen,product_aantal_delen,product_aantal_gangen,product_aantal_gaten,product_aantal_in_set,product_aantal_meetnaalden,product_afleesbaarheid_d_hl,product_aflezing,product_aflezing_boven,product_aflezing_onder,product_afm,product_afmeting,product_afmetingen,product_afmetingen_bxhxd_mm,product_afmetingen_lxbxh,product_afmetingen_voet,product_b,product_bandbreedte,product_batterij_uitrusting,product_bedrijfstemperatuur,product_bedrijfstijd_bij_20,product_beenbreedte,product_beenlengte,product_behuizing_,product_behuizings_,product_bekbreedte,product_beklengte,product_bereik,product_beschermingsgraad,product_beugeldiepte,product_binnenmeting,product_bladlengte,product_breedte,product_bruglengte,product_c,product_centerafstand,product_centerhoogte,product_compatibiliteit,product_conus,product_diepte,product_dieptemeetstift,product_diepteschuifmaat,product_dikte,product_dikte_van_de_delen,product_dioptrien,product_doorsnede,product_doorsnede_sterk_been,product_doorsnede_zwak_been,product_draagvermogen,product_dwarsarm_,product_dwarsdoorsnede_liniaal,product_dwarsdoorsnede_van_liniaal,product_foutmarge,product_frame,product_gangen_per,product_geheugen_splitlap,product_geleider,product_gewicht,product_gewicht_ca,product_golflengte,product_gradenboog,product_greep,product_groefbreedte,product_groefdiepte,product_grondplaat,product_grootte,product_hardheid,product_hechtvermogen,product_hechtvermogen_n,product_herhaalmarge,product_hoektolerantie,product_hoogte,product_hoogte_magneetvoet,product_inhoud,product_interface,product_interne_datageheugenplaats,product_inwendige_meting,product_klasse,product_kleinst_testoppervlak_convex,product_kogel_,product_kolom_,product_kwaliteit,product_lak,product_lengte,product_lengte_meetelement,product_lengte_verstelbare_meetbek,product_lens,product_lens_,product_lensgrootte,product_lichtkleur,product_maateenheden,product_magneet_,product_magneetvoet_lxbxh,product_magneetvoethouder,product_maten,product_materiaal,product_max_hoogte_van_het_object,product_meetbereik,product_meetbereik_,product_meetbereik_infrarood,product_meetbereik_k_type_sensor,product_meetbereik_leeb,product_meetbereik_mm,product_meetbereik_omgevingssensor,product_meetcontactlengte_bew,product_meetcontactlengte_vast,product_meetdiepte,product_meetdiepte_met_verlengstuk,product_meethoogte,product_meetklok_,product_meetklokhouder,product_meetkop_,product_meetkracht,product_meetnaalden,product_meetoptiek,product_meetplaat_,product_meetschijven,product_meetstift,product_meetstift_,product_meetstiftlengte,product_meettaster,product_meettrommel_,product_meetvlak,product_meetvlakken,product_meetvlakken_,product_merk,product_model,product_naald_,product_nauwkeurigheid,product_nominale_maat,product_,product_onderverdeling,product_ophanging,product_oplegvlak,product_oppervlak,product_opslagtemperatuur,product_parallelliteitstolerantie,product_passend_bij,product_passerpunt,product_prisma_opzetstuk_,product_prismahoek,product_profieldikte,product_profielgewicht,product_punt_,product_punthoek_meetbek,product_puntlengte,product_radii_in,product_schaalbecijfering,product_schijf_,product_schijfafstand_max,product_schroefdraad,product_schroefdraad_magneetvoet,product_schroefdraad__x_spoed,product_schuifmaat,product_schuifmaat_met_punten,product_schuifmaat_zonder_punten,product_setinhoud,product_slaglengte,product_sleufbreedte,product_spindel_,product_spindelspoed,product_spoed,product_statief_,product_t_sleufbreedte,product_taal,product_tafeloppervlak,product_tasterlengte,product_tasterweg,product_tastkogel,product_tastpunt,product_tekstuitbreiding_lang,product_testoppervlak_concaaf,product_tijdsaanduiding,product_toepassingsgebied,product_tolerantie,product_totale_hoogte,product_trommel_,product_type,product_type_meetcontact,product_uitgangsvermogen,product_uitvoering,product_uitwendige_meting,product_variant,product_verdeling,product_vergroting,product_verlengstukken,product_verspringing_meetvlakken,product_vlakheidsafwijking,product_voor_as_,product_voor_assen_tot_,product_voor_cirkel_,product_voor_doorsnede,product_voor_het_meten_van_de_spoed,product_voor_meetbereik,product_voor_omtrek,product_voor_schroefdraad,product_voor_spiraalboren_tot_,product_voor_testpen_,product_vorm,product_vrij_hefslag,product_werkafstand,product_werkbereik,product_werkbereik_kolomdwarsarm,product_werkbereik_,product_werkstuk__max,product_wgnr,product_wielomtrek,product_wijzeromwenteling,product_wijzerplaat_schaalverdeling,product_zendbereik,product_zuil_hoogte
         */
        $attributeCodes = [
            'product_1_wijzeromwenteling' => '1 wijzeromwenteling',
            'product_a' => 'a',
            'product_aanslaglengte' => 'Aanslaglengte',
            'product_aantal' => 'Aantal',
            'product_aantal_bladen' => 'Aantal bladen',
            'product_aantal_delen' => 'Aantal delen',
            'product_aantal_gangen' => 'Aantal gangen',
            'product_aantal_gaten' => 'Aantal gaten',
            'product_aantal_in_set' => 'Aantal in set',
            'product_aantal_meetnaalden' => 'Aantal meetnaalden',
            'product_afleesbaarheid_d_hl' => 'Afleesbaarheid (d) HL',
            'product_aflezing' => 'Aflezing',
            'product_aflezing_boven' => 'Aflezing boven',
            'product_aflezing_onder' => 'Aflezing onder',
            'product_afm' => 'Afm.',
            'product_afmeting' => 'Afmeting',
            'product_afmetingen' => 'Afmetingen',
            'product_afmetingen_bxhxd_mm' => 'Afmetingen (BxHxD) mm',
            'product_afmetingen_lxbxh' => 'Afmetingen (lxbxh)',
            'product_afmetingen_voet' => 'Afmetingen voet',
            'product_b' => 'b',
            'product_bandbreedte' => 'Bandbreedte',
            'product_batterij_uitrusting' => 'Batterij-uitrusting',
            'product_bedrijfstemperatuur' => 'Bedrijfstemperatuur',
            'product_bedrijfstijd_bij_20' => 'Bedrijfstijd (bij 20Â°)',
            'product_beenbreedte' => 'Beenbreedte',
            'product_beenlengte' => 'Beenlengte',
            'product_behuizing_' => 'Behuizing-Ã˜',
            'product_behuizings_' => 'Behuizings-Ã˜',
            'product_bekbreedte' => 'Bekbreedte',
            'product_beklengte' => 'Beklengte',
            'product_bereik' => 'Bereik',
            'product_beschermingsgraad' => 'Beschermingsgraad',
            'product_beugeldiepte' => 'Beugeldiepte',
            'product_binnenmeting' => 'Binnenmeting',
            'product_bladlengte' => 'Bladlengte',
            'product_breedte' => 'Breedte',
            'product_bruglengte' => 'Bruglengte',
            'product_c' => 'c',
            'product_centerafstand' => 'Centerafstand',
            'product_centerhoogte' => 'Centerhoogte',
            'product_compatibiliteit' => 'Compatibiliteit',
            'product_conus' => 'Conus',
            'product_diepte' => 'Diepte',
            'product_dieptemeetstift' => 'Dieptemeetstift',
            'product_diepteschuifmaat' => 'Diepteschuifmaat',
            'product_dikte' => 'Dikte',
            'product_dikte_van_de_delen' => 'Dikte van de delen',
            'product_dioptrien' => 'DioptrieÃ«n',
            'product_doorsnede' => 'Doorsnede',
            'product_doorsnede_sterk_been' => 'Doorsnede sterk been',
            'product_doorsnede_zwak_been' => 'Doorsnede zwak been',
            'product_draagvermogen' => 'Draagvermogen',
            'product_dwarsarm_' => 'Dwarsarm-Ã˜',
            'product_dwarsdoorsnede_liniaal' => 'Dwarsdoorsnede liniaal',
            'product_dwarsdoorsnede_van_liniaal' => 'Dwarsdoorsnede van liniaal',
            'product_foutmarge' => 'Foutmarge',
            'product_frame' => 'Frame',
            'product_gangen_per' => 'Gangen per',
            'product_geheugen_splitlap' => 'Geheugen Split/Lap',
            'product_geleider' => 'Geleider',
            'product_gewicht' => 'Gewicht:',
            'product_gewicht_ca' => 'Gewicht ca.',
            'product_golflengte' => 'Golflengte',
            'product_gradenboog' => 'Gradenboog',
            'product_greep' => 'Greep',
            'product_groefbreedte' => 'Groefbreedte',
            'product_groefdiepte' => 'Groefdiepte',
            'product_grondplaat' => 'Grondplaat',
            'product_grootte' => 'Grootte',
            'product_hardheid' => 'Hardheid',
            'product_hechtvermogen' => 'Hechtvermogen',
            'product_hechtvermogen_n' => 'Hechtvermogen N',
            'product_herhaalmarge' => 'Herhaalmarge',
            'product_hoektolerantie' => 'Hoektolerantie',
            'product_hoogte' => 'Hoogte',
            'product_hoogte_magneetvoet' => 'Hoogte magneetvoet',
            'product_inhoud' => 'Inhoud',
            'product_interface' => 'Interface',
            'product_interne_datageheugenplaats' => 'Interne datageheugenplaats',
            'product_inwendige_meting' => 'Inwendige meting',
            'product_klasse' => 'Klasse',
            'product_kleinst_testoppervlak_convex' => 'Kleinst testoppervlak convex',
            'product_kogel_' => 'Kogel-Ã˜',
            'product_kolom_' => 'Kolom-Ã˜',
            'product_kwaliteit' => 'Kwaliteit',
            'product_lak' => 'Lak',
            'product_lengte' => 'Lengte',
            'product_lengte_meetelement' => 'Lengte meetelement',
            'product_lengte_verstelbare_meetbek' => 'Lengte verstelbare meetbek',
            'product_lens' => 'Lens',
            'product_lens_' => 'Lens-Ã˜',
            'product_lensgrootte' => 'Lensgrootte',
            'product_lichtkleur' => 'Lichtkleur',
            'product_maateenheden' => 'Maateenheden',
            'product_magneet_' => 'Magneet-Ã˜',
            'product_magneetvoet_lxbxh' => 'Magneetvoet lxbxh',
            'product_magneetvoethouder' => 'Magneetvoethouder',
            'product_maten' => 'Maten',
            'product_materiaal' => 'Materiaal',
            'product_max_hoogte_van_het_object' => 'Max. hoogte van het object',
            'product_meetbereik' => 'Meetbereik',
            'product_meetbereik_' => 'Meetbereik Â±',
            'product_meetbereik_infrarood' => 'Meetbereik infrarood',
            'product_meetbereik_k_type_sensor' => 'Meetbereik K-type-sensor',
            'product_meetbereik_leeb' => 'Meetbereik Leeb',
            'product_meetbereik_mm' => 'Meetbereik mm',
            'product_meetbereik_omgevingssensor' => 'Meetbereik omgevingssensor',
            'product_meetcontactlengte_bew' => 'Meetcontactlengte bew.',
            'product_meetcontactlengte_vast' => 'Meetcontactlengte vast',
            'product_meetdiepte' => 'Meetdiepte',
            'product_meetdiepte_met_verlengstuk' => 'Meetdiepte met verlengstuk',
            'product_meethoogte' => 'Meethoogte',
            'product_meetklok_' => 'Meetklok-Ã˜',
            'product_meetklokhouder' => 'Meetklokhouder',
            'product_meetkop_' => 'Meetkop-Ã˜',
            'product_meetkracht' => 'Meetkracht',
            'product_meetnaalden' => 'Meetnaalden',
            'product_meetoptiek' => 'Meetoptiek',
            'product_meetplaat_' => 'Meetplaat-Ã˜',
            'product_meetschijven' => 'Meetschijven',
            'product_meetstift' => 'Meetstift',
            'product_meetstift_' => 'Meetstift-Ã˜',
            'product_meetstiftlengte' => 'Meetstiftlengte',
            'product_meettaster' => 'Meettaster',
            'product_meettrommel_' => 'Meettrommel-Ã˜',
            'product_meetvlak' => 'Meetvlak',
            'product_meetvlakken' => 'Meetvlakken',
            'product_meetvlakken_' => 'Meetvlakken-Ã˜',
            'product_merk' => 'Merk',
            'product_model' => 'Model:',
            'product_naald_' => 'Naald-Ã˜',
            'product_nauwkeurigheid' => 'Nauwkeurigheid',
            'product_nominale_maat' => 'Nominale maat',
            'product_' => 'Ã˜',
            'product_onderverdeling' => 'Onderverdeling',
            'product_ophanging' => 'Ophanging',
            'product_oplegvlak' => 'Oplegvlak',
            'product_oppervlak' => 'Oppervlak',
            'product_opslagtemperatuur' => 'Opslagtemperatuur',
            'product_parallelliteitstolerantie' => 'Parallelliteitstolerantie',
            'product_passend_bij' => 'Passend bij',
            'product_passerpunt' => 'Passerpunt',
            'product_prisma_opzetstuk_' => 'Prisma opzetstuk Ã˜',
            'product_prismahoek' => 'Prismahoek',
            'product_profieldikte' => 'Profieldikte',
            'product_profielgewicht' => 'Profielgewicht',
            'product_punt_' => 'Punt-Ã˜',
            'product_punthoek_meetbek' => 'Punthoek meetbek',
            'product_puntlengte' => 'Puntlengte',
            'product_radii_in' => 'Radii in',
            'product_schaalbecijfering' => 'Schaalbecijfering',
            'product_schijf_' => 'Schijf-Ã˜',
            'product_schijfafstand_max' => 'Schijfafstand max.',
            'product_schroefdraad' => 'Schroefdraad',
            'product_schroefdraad_magneetvoet' => 'Schroefdraad magneetvoet',
            'product_schroefdraad__x_spoed' => 'Schroefdraad-Ã˜ x spoed',
            'product_schuifmaat' => 'Schuifmaat',
            'product_schuifmaat_met_punten' => 'Schuifmaat met punten',
            'product_schuifmaat_zonder_punten' => 'Schuifmaat zonder punten',
            'product_setinhoud' => 'Setinhoud',
            'product_slaglengte' => 'Slaglengte',
            'product_sleufbreedte' => 'Sleufbreedte',
            'product_spindel_' => 'Spindel-Ã˜',
            'product_spindelspoed' => 'Spindelspoed',
            'product_spoed' => 'Spoed',
            'product_statief_' => 'Statief-Ã˜',
            'product_t_sleufbreedte' => 'T-sleufbreedte',
            'product_taal' => 'Taal',
            'product_tafeloppervlak' => 'Tafeloppervlak',
            'product_tasterlengte' => 'Tasterlengte',
            'product_tasterweg' => 'Tasterweg',
            'product_tastkogel' => 'Tastkogel',
            'product_tastpunt' => 'Tastpunt',
            'product_tekstuitbreiding_lang' => 'Tekstuitbreiding lang',
            'product_testoppervlak_concaaf' => 'Testoppervlak concaaf',
            'product_tijdsaanduiding' => 'Tijdsaanduiding',
            'product_toepassingsgebied' => 'Toepassingsgebied',
            'product_tolerantie' => 'Tolerantie',
            'product_totale_hoogte' => 'Totale hoogte',
            'product_trommel_' => 'Trommel-Ã˜',
            'product_type' => 'Type',
            'product_type_meetcontact' => 'Type meetcontact:',
            'product_uitgangsvermogen' => 'Uitgangsvermogen',
            'product_uitvoering' => 'Uitvoering',
            'product_uitwendige_meting' => 'Uitwendige meting',
            'product_variant' => 'Variant',
            'product_verdeling' => 'Verdeling',
            'product_vergroting' => 'Vergroting',
            'product_verlengstukken' => 'Verlengstukken',
            'product_verspringing_meetvlakken' => 'Verspringing meetvlakken',
            'product_vlakheidsafwijking' => 'Vlakheidsafwijking',
            'product_voor_as_' => 'voor as-Ã˜',
            'product_voor_assen_tot_' => 'Voor assen tot Ã˜',
            'product_voor_cirkel_' => 'Voor cirkel-Ã˜',
            'product_voor_doorsnede' => 'Voor doorsnede',
            'product_voor_het_meten_van_de_spoed' => 'voor het meten van de spoed',
            'product_voor_meetbereik' => 'voor meetbereik',
            'product_voor_omtrek' => 'Voor omtrek',
            'product_voor_schroefdraad' => 'voor schroefdraad',
            'product_voor_spiraalboren_tot_' => 'Voor spiraalboren tot Ã˜',
            'product_voor_testpen_' => 'Voor testpen-Ã˜',
            'product_vorm' => 'Vorm',
            'product_vrij_hefslag' => 'Vrij hefslag',
            'product_werkafstand' => 'Werkafstand',
            'product_werkbereik' => 'Werkbereik',
            'product_werkbereik_kolomdwarsarm' => 'Werkbereik (kolom/dwarsarm)',
            'product_werkbereik_' => 'Werkbereik Ã˜',
            'product_werkstuk__max' => 'Werkstuk-Ã˜ max.',
            'product_wgnr' => 'WGNr.',
            'product_wielomtrek' => 'Wielomtrek',
            'product_wijzeromwenteling' => 'Wijzeromwenteling',
            'product_wijzerplaat_schaalverdeling' => 'Wijzerplaat schaalverdeling',
            'product_zendbereik' => 'Zendbereik',
            'product_zuil_hoogte' => 'Zuil hoogte',
        ];

        foreach ($attributeCodes as $attributeCode => $attributeLabel) {
            if ($eavSetup->getAttributeId(\Magento\Catalog\Model\Product::ENTITY, $attributeCode)) {
                continue;
            }

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
            $attributeGroupName = 'Meettechniek';
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