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

class AddSoldeerAttributes implements DataPatchInterface
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
        $attributeGroup = 'Werkplaats';

        /**
         * TODO: CSV header columns
         * product_1_liter_oliebinder_bindt,product_aanbev_werkdruk,product_aansluiting,product_aansluitschroefdraad,product_aansluitspanning,product_accutype,product_afleesbereik,product_afm,product_afmeting,product_afmetingen,product_afmetingen_l_x_b_x_h,product_apparaatslag,product_bandbreedte,product_bandbreedte_x_banddikte,product_banddikte,product_bandlengte,product_batterij_uitrusting,product_bedrijfsdruk,product_bedrijfsmodus,product_bedrijfstemperatuur_c,product_belasting_per_element,product_binnen_,product_binnendraad,product_boor_,product_boorgat_,product_brandduur,product_brandtijd_leds_puntlicht,product_brandtijd_puntlicht,product_breedte,product_buislengte,product_buiten_,product_capaciteit,product_clip,product_contactdoos,product_contactdozen,product_din_bescherming,product_dn,product_doorstroming,product_doorstroming_lucht,product_draaibanken,product_druk,product_drukoverbrenging,product_drukvermogen,product_drum_,product_etikettenmaat,product_filterelement,product_freesmachines,product_gasverbruik,product_geschikt_voor,product_geschikt_voor_emmer_ca,product_gewicht,product_gewicht_ca,product_grootte,product_grootte_lettertype,product_grootte_,product_hoogte,product_hoogte_h,product_in_uitschakeltijden_per_dag,product_inhoud,product_inklinkkracht,product_inklinkkracht_bij_6_bar,product_kabelkwaliteit,product_kabellengte,product_kabeltype,product_kleur,product_kleursysteem,product_klinkbare_materiaaldikte,product_klinkschacht__x_lengte,product_kop_,product_korrel,product_kwast_,product_kwastbreedte,product_lak,product_ledig_gewicht,product_leds,product_lengte,product_lengte_geopend,product_lengte_gesl,product_lengte_van_de_handgreep,product_lichtbereik,product_lichtsterkte,product_luchtaansluiting,product_luchtverbruik_per_klinknagel,product_lus,product_machines_algemeen,product_magneet,product_mantel_,product_marke,product_materiaaldikte,product_max_bedrijfsdruk,product_max_dikte_machinevoet,product_max_ingangsdruk,product_max_mediumtemperatuur,product_max_opening,product_mediumtemperatuur_c,product_mediumtemperatuur_max_60c,product_meetbereik,product_merk,product_model,product_nietlengte,product_nivelleerhoogte_ni,product_nominaal_opgenomen_vermogen,product_nominale_afmeting_,product_nominale_breedte,product_nominale_inhoud,product_,product__d,product__x_lengte,product_opgenomen_verm_bij_opwarmen,product_opwarmtijd_280c,product_opwarmtijd_ca,product_persen_tot_110_slagenmin,product_persluchtslang,product_primaire_spanning,product_puntvorm,product_regeltechniek,product_rolbreedte,product_schakelafstand,product_schakeltijd_licht_donker,product_schepbreedte,product_scheplengte,product_schroefdraad,product_schroefdraad_g,product_secundaire_spanning,product_segmentslang,product_slang_binnen_,product_slangaansluiting,product_slanglengte,product_software,product_soldeerbouten,product_spanbereik,product_spanning,product_spijkerlengte,product_spiraal_,product_sproeierboring,product_standaardschroeflengte,product_statische_maximumlast,product_stralingshoek,product_streepbreedte_ca,product_stroomsterkte,product_sw,product_tapekleur,product_tekstkleur,product_tekstuitbreiding_lang,product_temperatuur_soldeerstift,product_temperatuurbereik,product_toepassing,product_tot_pijp_,product_trommel_,product_type,product_type_nietjes,product_type_spijkers,product_typeshouder,product_uitvoering,product_verlichtingsbereik,product_verlichtingswijdte,product_vermogen,product_vermogen_350c,product_viscositeit,product_vlakslijpmachines,product_voor_aansluitformaat,product_voor_consistentieklasse_nlgi,product_voor_emmer_binnen_,product_voor_jerrycans,product_voor_max_emmerhoogte,product_voor_model,product_voor_patronen,product_voor_rolbreedte,product_voor_rollengte_tot,product_voor_schroefdraad,product_voor_slang,product_voor_soldeerbouten,product_voor_vaten,product_voor_zakken,product_vorm,product_wanddikte,product_werkdruk,product_werkdruk_bij_23_c,product_wgnr,product_zuigleiding,product_zwenkbare_kop,product_zwenkbereik
         */
        $attributeCodes = [
            'product_1_liter_oliebinder_bindt' => '1 liter oliebinder bindt',
            'product_aanbev_werkdruk' => 'Aanbev. Werkdruk',
            'product_aansluiting' => 'Aansluiting',
            'product_aansluitschroefdraad' => 'Aansluitschroefdraad',
            'product_aansluitspanning' => 'Aansluitspanning',
            'product_accutype' => 'Accutype',
            'product_afleesbereik' => 'Afleesbereik',
            'product_afm' => 'Afm.',
            'product_afmeting' => 'Afmeting',
            'product_afmetingen' => 'Afmetingen',
            'product_afmetingen_l_x_b_x_h' => 'Afmetingen L x B x H',
            'product_apparaatslag' => 'Apparaatslag',
            'product_bandbreedte' => 'Bandbreedte',
            'product_bandbreedte_x_banddikte' => 'Bandbreedte x banddikte',
            'product_banddikte' => 'Banddikte',
            'product_bandlengte' => 'Bandlengte',
            'product_batterij_uitrusting' => 'Batterij-uitrusting',
            'product_bedrijfsdruk' => 'Bedrijfsdruk',
            'product_bedrijfsmodus' => 'Bedrijfsmodus',
            'product_bedrijfstemperatuur_c' => 'Bedrijfstemperatuur Â°C',
            'product_belasting_per_element' => 'Belasting per element',
            'product_binnen_' => 'Binnen-Ã˜',
            'product_binnendraad' => 'Binnendraad',
            'product_boor_' => 'Boor-Ã˜',
            'product_boorgat_' => 'Boorgat-Ã˜',
            'product_brandduur' => 'Brandduur',
            'product_brandtijd_leds_puntlicht' => 'Brandtijd LED\'s puntlicht',
            'product_brandtijd_puntlicht' => 'Brandtijd puntlicht',
            'product_breedte' => 'Breedte',
            'product_buislengte' => 'Buislengte',
            'product_buiten_' => 'Buiten-Ã˜',
            'product_capaciteit' => 'Capaciteit',
            'product_clip' => 'Clip',
            'product_contactdoos' => 'Contactdoos',
            'product_contactdozen' => 'Contactdozen',
            'product_din_bescherming' => 'DIN-bescherming',
            'product_dn' => 'DN',
            'product_doorstroming' => 'Doorstroming',
            'product_doorstroming_lucht' => 'Doorstroming (lucht)',
            'product_draaibanken' => 'Draaibanken',
            'product_druk' => 'Druk',
            'product_drukoverbrenging' => 'Drukoverbrenging',
            'product_drukvermogen' => 'Drukvermogen',
            'product_drum_' => 'Drum Ã˜',
            'product_etikettenmaat' => 'Etikettenmaat',
            'product_filterelement' => 'Filterelement',
            'product_freesmachines' => 'Freesmachines',
            'product_gasverbruik' => 'Gasverbruik',
            'product_geschikt_voor' => 'geschikt voor',
            'product_geschikt_voor_emmer_ca' => 'Geschikt voor emmer ca.',
            'product_gewicht' => 'Gewicht',
            'product_gewicht_ca' => 'Gewicht ca.',
            'product_grootte' => 'Grootte',
            'product_grootte_lettertype' => 'Grootte lettertype',
            'product_grootte_' => 'Grootte-Ã˜',
            'product_hoogte' => 'Hoogte',
            'product_hoogte_h' => 'Hoogte H',
            'product_in_uitschakeltijden_per_dag' => 'In-/uitschakeltijden per dag',
            'product_inhoud' => 'Inhoud',
            'product_inklinkkracht' => 'Inklinkkracht',
            'product_inklinkkracht_bij_6_bar' => 'Inklinkkracht bij 6 bar',
            'product_kabelkwaliteit' => 'Kabelkwaliteit',
            'product_kabellengte' => 'Kabellengte',
            'product_kabeltype' => 'Kabeltype',
            'product_kleur' => 'Kleur',
            'product_kleursysteem' => 'Kleursysteem',
            'product_klinkbare_materiaaldikte' => 'Klinkbare materiaaldikte',
            'product_klinkschacht__x_lengte' => 'Klinkschacht-Ã˜ x lengte',
            'product_kop_' => 'Kop Ã˜',
            'product_korrel' => 'Korrel',
            'product_kwast_' => 'Kwast-Ã˜',
            'product_kwastbreedte' => 'Kwastbreedte',
            'product_lak' => 'Lak',
            'product_ledig_gewicht' => 'Ledig gewicht',
            'product_leds' => 'LED\'s',
            'product_lengte' => 'Lengte',
            'product_lengte_geopend' => 'Lengte geopend',
            'product_lengte_gesl' => 'Lengte gesl.',
            'product_lengte_van_de_handgreep' => 'Lengte van de handgreep',
            'product_lichtbereik' => 'Lichtbereik',
            'product_lichtsterkte' => 'Lichtsterkte',
            'product_luchtaansluiting' => 'Luchtaansluiting',
            'product_luchtverbruik_per_klinknagel' => 'Luchtverbruik per klinknagel',
            'product_lus' => 'Lus',
            'product_machines_algemeen' => 'Machines algemeen',
            'product_magneet' => 'Magneet',
            'product_mantel_' => 'Mantel-Ã˜',
            'product_marke' => 'Marke',
            'product_materiaaldikte' => 'Materiaaldikte',
            'product_max_bedrijfsdruk' => 'max. bedrijfsdruk',
            'product_max_dikte_machinevoet' => 'Max. dikte machinevoet',
            'product_max_ingangsdruk' => 'max. ingangsdruk',
            'product_max_mediumtemperatuur' => 'Max. mediumtemperatuur',
            'product_max_opening' => 'Max. opening',
            'product_mediumtemperatuur_c' => 'Mediumtemperatuur Â°C',
            'product_mediumtemperatuur_max_60c' => 'Mediumtemperatuur: max. 60Â°C',
            'product_meetbereik' => 'Meetbereik',
            'product_merk' => 'Merk',
            'product_model' => 'Model',
            'product_nietlengte' => 'Nietlengte',
            'product_nivelleerhoogte_ni' => 'Nivelleerhoogte Ni',
            'product_nominaal_opgenomen_vermogen' => 'Nominaal opgenomen vermogen',
            'product_nominale_afmeting_' => 'Nominale afmeting Ã˜',
            'product_nominale_breedte' => 'Nominale breedte',
            'product_nominale_inhoud' => 'Nominale inhoud',
            'product_' => 'Ã˜',
            'product__d' => 'Ã˜ D',
            'product__x_lengte' => 'Ã˜ x lengte',
            'product_opgenomen_verm_bij_opwarmen' => 'Opgenomen verm. bij opwarmen',
            'product_opwarmtijd_280c' => 'Opwarmtijd (280Â°C)',
            'product_opwarmtijd_ca' => 'Opwarmtijd ca.',
            'product_persen_tot_110_slagenmin' => 'Persen tot 110 slagen/min',
            'product_persluchtslang' => 'Persluchtslang',
            'product_primaire_spanning' => 'Primaire spanning',
            'product_puntvorm' => 'Puntvorm',
            'product_regeltechniek' => 'Regeltechniek',
            'product_rolbreedte' => 'Rolbreedte',
            'product_schakelafstand' => 'Schakelafstand',
            'product_schakeltijd_licht_donker' => 'Schakeltijd licht-donker',
            'product_schepbreedte' => 'Schepbreedte',
            'product_scheplengte' => 'Scheplengte',
            'product_schroefdraad' => 'Schroefdraad',
            'product_schroefdraad_g' => 'Schroefdraad G',
            'product_secundaire_spanning' => 'Secundaire spanning',
            'product_segmentslang' => 'Segmentslang',
            'product_slang_binnen_' => 'Slang binnen-Ã˜',
            'product_slangaansluiting' => 'Slangaansluiting',
            'product_slanglengte' => 'Slanglengte',
            'product_software' => 'Software',
            'product_soldeerbouten' => 'Soldeerbouten',
            'product_spanbereik' => 'Spanbereik',
            'product_spanning' => 'Spanning',
            'product_spijkerlengte' => 'Spijkerlengte',
            'product_spiraal_' => 'Spiraal-Ã˜',
            'product_sproeierboring' => 'Sproeierboring',
            'product_standaardschroeflengte' => 'Standaardschroeflengte',
            'product_statische_maximumlast' => 'statische maximumlast',
            'product_stralingshoek' => 'Stralingshoek',
            'product_streepbreedte_ca' => 'Streepbreedte ca.',
            'product_stroomsterkte' => 'Stroomsterkte',
            'product_sw' => 'SW',
            'product_tapekleur' => 'Tapekleur',
            'product_tekstkleur' => 'Tekstkleur',
            'product_tekstuitbreiding_lang' => 'Tekstuitbreiding lang',
            'product_temperatuur_soldeerstift' => 'Temperatuur soldeerstift',
            'product_temperatuurbereik' => 'Temperatuurbereik',
            'product_toepassing' => 'Toepassing',
            'product_tot_pijp_' => 'Tot pijp-Ã˜',
            'product_trommel_' => 'Trommel-Ã˜',
            'product_type' => 'Type',
            'product_type_nietjes' => 'Type nietjes',
            'product_type_spijkers' => 'Type spijkers',
            'product_typeshouder' => 'Types/houder',
            'product_uitvoering' => 'Uitvoering',
            'product_verlichtingsbereik' => 'Verlichtingsbereik',
            'product_verlichtingswijdte' => 'Verlichtingswijdte',
            'product_vermogen' => 'Vermogen',
            'product_vermogen_350c' => 'Vermogen (350Â°C)',
            'product_viscositeit' => 'Viscositeit',
            'product_vlakslijpmachines' => 'Vlakslijpmachines',
            'product_voor_aansluitformaat' => 'voor aansluitformaat',
            'product_voor_consistentieklasse_nlgi' => 'Voor consistentieklasse NLGI',
            'product_voor_emmer_binnen_' => 'voor emmer-binnen-Ã˜',
            'product_voor_jerrycans' => 'voor jerrycans',
            'product_voor_max_emmerhoogte' => 'voor max. emmerhoogte',
            'product_voor_model' => 'Voor model',
            'product_voor_patronen' => 'voor patronen',
            'product_voor_rolbreedte' => 'Voor rolbreedte',
            'product_voor_rollengte_tot' => 'Voor rollengte tot',
            'product_voor_schroefdraad' => 'voor schroefdraad',
            'product_voor_slang' => 'Voor slang',
            'product_voor_soldeerbouten' => 'Voor soldeerbouten',
            'product_voor_vaten' => 'voor vaten',
            'product_voor_zakken' => 'voor zakken',
            'product_vorm' => 'Vorm',
            'product_wanddikte' => 'Wanddikte',
            'product_werkdruk' => 'Werkdruk',
            'product_werkdruk_bij_23_c' => 'Werkdruk bij 23 Â°C',
            'product_wgnr' => 'WGNr.',
            'product_zuigleiding' => 'Zuigleiding',
            'product_zwenkbare_kop' => 'Zwenkbare kop',
            'product_zwenkbereik' => 'Zwenkbereik',
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
            $attributeGroupName = 'Werkplaats';
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