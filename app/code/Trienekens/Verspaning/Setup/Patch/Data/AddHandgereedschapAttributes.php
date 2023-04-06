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

class AddHandgereedschapAttributes implements DataPatchInterface
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
        $attributeGroup = 'Handgereedschap';

        /**
         * TODO: CSV header columns
         * product_aambeeldvlak,product_aandrijfvierkant,product_afm,product_afmetingen,product_afmetingen_lxbxh__gewicht,product_afstripcapaci_enkele_draad,product_as_,product_awg,product_band_,product_batterij_uitrusting,product_bedrijfstemperatuur,product_beendikte,product_beenlengte,product_bekbreedte,product_bekdikte,product_bekhoogte,product_beklengte,product_beschermingsgraad,product_beschermingswijze,product_bindkracht,product_boring_,product_breedte,product_buigradius,product_bundel_,product_capaciteit,product_contactvlak_b_x_h,product_dikte,product_doorgang,product_doorgangdiode,product_doorgangstest,product_doorslagsterkte_kvmm,product_dopsleutel_binnen_,product_dopsleutel_buiten_,product_draagverm_vol_uitgetrokken,product_draaistift,product_draaiveld,product_draaiveldrichtingsaanduiding,product_externe_spanningsindicatie,product_fasetest,product_fasevolgordetest,product_fircd_aarddraad_teststroom,product_fijninstelling,product_frequentie,product_frequentiebereik,product_gat_,product_gatafstand,product_gatenafstand_,product_gelijkspanning,product_gelijkstroom,product_geschikt_voor,product_gevoeligheid,product_gewicht,product_gewicht_ca,product_glijrail,product_grootte,product_grootte_schroefbevestiging,product_halfgeleidertest,product_halfharde_draad,product_harde_draad,product_hellingmeter,product_hold_functie,product_hoogte,product_inbouwmaten,product_inhoud,product_isolatiemateriaal_klasse,product_isolatieweerstand,product_kabeldiam_adereindhulzen,product_kabeldiameter,product_kabellengte,product_klembreedte,product_kleurcodering,product_kling_,product_klinglengte,product_klinknagellengte,product_kompaspeiling_uitlijning,product_kopdikte,product_kortsluitstr_gelijkstroom,product_kortsluitstroom,product_krimpbereik,product_krimptemperatuur_min,product_krimpzones,product_laagohmige_weerstand,product_lastinschakeling,product_lengte,product_lengte_gesloten,product_lengte_geslotenopen,product_lengte_verbleiding_meetlus,product_lengte_x_,product_lengtekrimp_max,product_levering_in,product_lusimpedantie_l_pe,product_maat,product_max_doorgang,product_max_kabelbinderbreedte,product_max_kabelbinderdikte,product_meetbereik,product_meetcategorie,product_meetluslengte,product_meetmethode,product_meetpen,product_meetpuntverlichting,product_meetwaardengeheugen,product_merk,product_model,product_netimpedantie_l_nl,product_nok_,product_nokafstand,product_noklengte,product_nullastspan_gelijkstroom,product_,product__x_lengte,product_omzettingsverhouding,product_opname,product_pianodraad,product_polariteitstest,product_prisma,product_randaardeweerstand,product_realtime_klok,product_ringdikte,product_schacht_,product_setinhoud,product_sleutelwijdte,product_sleutelwijdte_max,product_sleutelwijdte_schacht,product_snij__19_hrc,product_snij__40_hrc,product_snij__48_hrc,product_snijcapaciteit_,product_snijden__19_hrc,product_snijden__40_hrc,product_snijden__48_hrc,product_spanbereik_buis_buiten_,product_spandiepte,product_spanning,product_spanningsbereik,product_spanwijdte,product_spanwijdte_diepte,product_spindelaantal,product_spreidbreedte,product_staaldraad,product_steekmaat,product_stroomverbruik,product_tangopening,product_tekstuitbreiding_lang,product_temp_module_en_omgeving,product_temperatuur,product_testspanning,product_teststroom,product_teststroom_gelijkstroom,product_toepassing,product_toepassingsgebied,product_totale_lengte,product_trappen,product_trekbelasting,product_trekkracht,product_trilalarm,product_uitvoering,product_verschilstroom,product_voor_aslager_,product_voor_bekbreedte,product_voor_buizen,product_voor_draaistift_,product_voor_kabelbinderbreedte,product_voor_kabeldiameter,product_voor_lengte,product_voor_moeren,product_voor_nominale_breedte,product_voor_,product_voor_overstek,product_voor_raildikte_max,product_voor_schroef_,product_voor_schroeven,product_voor_sleutelwijdte,product_weergave,product_weerstand,product_werkbereik,product_werkbereik_,product_werktemperatuur,product_wgnr,product_wisselspanning,product_wisselstroom,product_wordt_geleverd_in,product_zachte_draad,product_zoninstraling
         */
        $attributeCodes = [
            'product_aambeeldvlak' => 'Aambeeldvlak',
            'product_aandrijfvierkant' => 'Aandrijfvierkant',
            'product_afm' => 'Afm.',
            'product_afmetingen' => 'Afmetingen',
            'product_afmetingen_lxbxh__gewicht' => 'Afmetingen (LxBxH) / gewicht',
            'product_afstripcapaci_enkele_draad' => 'Afstripcapaci. enkele draad',
            'product_as_' => 'As-Ã˜',
            'product_awg' => 'AWG',
            'product_band_' => 'Band-Ã˜',
            'product_batterij_uitrusting' => 'Batterij-uitrusting',
            'product_bedrijfstemperatuur' => 'Bedrijfstemperatuur',
            'product_beendikte' => 'Beendikte',
            'product_beenlengte' => 'Beenlengte',
            'product_bekbreedte' => 'Bekbreedte',
            'product_bekdikte' => 'Bekdikte',
            'product_bekhoogte' => 'Bekhoogte',
            'product_beklengte' => 'Beklengte',
            'product_beschermingsgraad' => 'Beschermingsgraad',
            'product_beschermingswijze' => 'Beschermingswijze',
            'product_bindkracht' => 'Bindkracht',
            'product_boring_' => 'Boring-Ã˜',
            'product_breedte' => 'Breedte',
            'product_buigradius' => 'Buigradius',
            'product_bundel_' => 'Bundel-Ã˜',
            'product_capaciteit' => 'Capaciteit',
            'product_contactvlak_b_x_h' => 'Contactvlak B x H',
            'product_dikte' => 'Dikte',
            'product_doorgang' => 'Doorgang',
            'product_doorgangdiode' => 'Doorgang/diode',
            'product_doorgangstest' => 'Doorgangstest',
            'product_doorslagsterkte_kvmm' => 'Doorslagsterkte kV/mm',
            'product_dopsleutel_binnen_' => 'Dopsleutel binnen-Ã˜',
            'product_dopsleutel_buiten_' => 'Dopsleutel buiten-Ã˜',
            'product_draagverm_vol_uitgetrokken' => 'Draagverm. vol. uitgetrokken',
            'product_draaistift' => 'Draaistift',
            'product_draaiveld' => 'Draaiveld',
            'product_draaiveldrichtingsaanduiding' => 'Draaiveldrichtingsaanduiding',
            'product_externe_spanningsindicatie' => 'Externe spanningsindicatie',
            'product_fasetest' => 'Fasetest',
            'product_fasevolgordetest' => 'Fasevolgordetest',
            'product_fircd_aarddraad_teststroom' => 'FI/RCD-aarddraad teststroom',
            'product_fijninstelling' => 'Fijninstelling',
            'product_frequentie' => 'Frequentie',
            'product_frequentiebereik' => 'Frequentiebereik',
            'product_gat_' => 'Gat-Ã˜',
            'product_gatafstand' => 'Gatafstand',
            'product_gatenafstand_' => 'Gatenafstand/-Ã˜',
            'product_gelijkspanning' => 'Gelijkspanning',
            'product_gelijkstroom' => 'Gelijkstroom',
            'product_geschikt_voor' => 'geschikt voor',
            'product_gevoeligheid' => 'Gevoeligheid',
            'product_gewicht' => 'Gewicht',
            'product_gewicht_ca' => 'Gewicht ca.',
            'product_glijrail' => 'Glijrail',
            'product_grootte' => 'Grootte',
            'product_grootte_schroefbevestiging' => 'Grootte schroefbevestiging',
            'product_halfgeleidertest' => 'Halfgeleidertest',
            'product_halfharde_draad' => 'halfharde draad',
            'product_harde_draad' => 'harde draad',
            'product_hellingmeter' => 'Hellingmeter',
            'product_hold_functie' => 'HOLD-functie',
            'product_hoogte' => 'Hoogte',
            'product_inbouwmaten' => 'Inbouwmaten:',
            'product_inhoud' => 'Inhoud',
            'product_isolatiemateriaal_klasse' => 'Isolatiemateriaal-klasse',
            'product_isolatieweerstand' => 'Isolatieweerstand',
            'product_kabeldiam_adereindhulzen' => 'Kabeldiam. (adereindhulzen)',
            'product_kabeldiameter' => 'Kabeldiameter',
            'product_kabellengte' => 'Kabellengte',
            'product_klembreedte' => 'Klembreedte',
            'product_kleurcodering' => 'Kleurcodering',
            'product_kling_' => 'Kling-Ã˜',
            'product_klinglengte' => 'Klinglengte',
            'product_klinknagellengte' => 'Klinknagellengte',
            'product_kompaspeiling_uitlijning' => 'Kompaspeiling (uitlijning)',
            'product_kopdikte' => 'Kopdikte',
            'product_kortsluitstr_gelijkstroom' => 'Kortsluitstr. (gelijkstroom)',
            'product_kortsluitstroom' => 'Kortsluitstroom',
            'product_krimpbereik' => 'Krimpbereik',
            'product_krimptemperatuur_min' => 'Krimptemperatuur min.',
            'product_krimpzones' => 'Krimpzones',
            'product_laagohmige_weerstand' => 'Laagohmige weerstand',
            'product_lastinschakeling' => 'Lastinschakeling',
            'product_lengte' => 'Lengte',
            'product_lengte_gesloten' => 'Lengte gesloten',
            'product_lengte_geslotenopen' => 'Lengte gesloten/open',
            'product_lengte_verbleiding_meetlus' => 'Lengte verb.leiding meetlus',
            'product_lengte_x_' => 'Lengte x Ã˜',
            'product_lengtekrimp_max' => 'Lengtekrimp max.',
            'product_levering_in' => 'Levering in',
            'product_lusimpedantie_l_pe' => 'Lusimpedantie (L-PE)',
            'product_maat' => 'Maat',
            'product_max_doorgang' => 'Max. doorgang',
            'product_max_kabelbinderbreedte' => 'Max. kabelbinderbreedte',
            'product_max_kabelbinderdikte' => 'Max. kabelbinderdikte',
            'product_meetbereik' => 'Meetbereik',
            'product_meetcategorie' => 'Meetcategorie',
            'product_meetluslengte' => 'Meetluslengte',
            'product_meetmethode' => 'Meetmethode',
            'product_meetpen' => 'Meetpen',
            'product_meetpuntverlichting' => 'Meetpuntverlichting',
            'product_meetwaardengeheugen' => 'Meetwaardengeheugen',
            'product_merk' => 'Merk',
            'product_model' => 'Model',
            'product_netimpedantie_l_nl' => 'Netimpedantie (L-N/L)',
            'product_nok_' => 'Nok-Ã˜',
            'product_nokafstand' => 'Nokafstand',
            'product_noklengte' => 'Noklengte',
            'product_nullastspan_gelijkstroom' => 'Nullastspan. (gelijkstroom)',
            'product_' => 'Ã˜',
            'product__x_lengte' => 'Ã˜ x lengte',
            'product_omzettingsverhouding' => 'Omzettingsverhouding',
            'product_opname' => 'Opname',
            'product_pianodraad' => 'Pianodraad',
            'product_polariteitstest' => 'Polariteitstest',
            'product_prisma' => 'Prisma',
            'product_randaardeweerstand' => 'Randaardeweerstand',
            'product_realtime_klok' => 'Realtime-klok',
            'product_ringdikte' => 'Ringdikte',
            'product_schacht_' => 'Schacht-Ã˜',
            'product_setinhoud' => 'Setinhoud',
            'product_sleutelwijdte' => 'Sleutelwijdte',
            'product_sleutelwijdte_max' => 'Sleutelwijdte max.',
            'product_sleutelwijdte_schacht' => 'Sleutelwijdte schacht',
            'product_snij__19_hrc' => 'Snij-Ã˜ 19 HRC',
            'product_snij__40_hrc' => 'Snij-Ã˜ 40 HRC',
            'product_snij__48_hrc' => 'Snij-Ã˜ 48 HRC',
            'product_snijcapaciteit_' => 'Snijcapaciteit Ã˜',
            'product_snijden__19_hrc' => 'Snijden Ã˜ 19 HRC',
            'product_snijden__40_hrc' => 'Snijden Ã˜ 40 HRC',
            'product_snijden__48_hrc' => 'Snijden Ã˜ 48 HRC',
            'product_spanbereik_buis_buiten_' => 'Spanbereik buis buiten-Ã˜',
            'product_spandiepte' => 'Spandiepte',
            'product_spanning' => 'Spanning',
            'product_spanningsbereik' => 'Spanningsbereik',
            'product_spanwijdte' => 'Spanwijdte',
            'product_spanwijdte_diepte' => 'Spanwijdte/-diepte',
            'product_spindelaantal' => 'Spindelaantal',
            'product_spreidbreedte' => 'Spreidbreedte',
            'product_staaldraad' => 'Staaldraad',
            'product_steekmaat' => 'Steekmaat',
            'product_stroomverbruik' => 'Stroomverbruik',
            'product_tangopening' => 'Tangopening',
            'product_tekstuitbreiding_lang' => 'Tekstuitbreiding lang',
            'product_temp_module_en_omgeving' => 'Temp. module en omgeving',
            'product_temperatuur' => 'Temperatuur',
            'product_testspanning' => 'Testspanning',
            'product_teststroom' => 'Teststroom',
            'product_teststroom_gelijkstroom' => 'Teststroom (gelijkstroom)',
            'product_toepassing' => 'Toepassing',
            'product_toepassingsgebied' => 'Toepassingsgebied',
            'product_totale_lengte' => 'Totale lengte',
            'product_trappen' => 'Trappen',
            'product_trekbelasting' => 'Trekbelasting',
            'product_trekkracht' => 'Trekkracht',
            'product_trilalarm' => 'Trilalarm',
            'product_uitvoering' => 'Uitvoering',
            'product_verschilstroom' => 'Verschilstroom',
            'product_voor_aslager_' => 'Voor as/lager-Ã˜',
            'product_voor_bekbreedte' => 'Voor bekbreedte',
            'product_voor_buizen' => 'Voor buizen',
            'product_voor_draaistift_' => 'Voor draaistift-Ã˜',
            'product_voor_kabelbinderbreedte' => 'voor kabelbinderbreedte',
            'product_voor_kabeldiameter' => 'Voor kabeldiameter',
            'product_voor_lengte' => 'Voor lengte',
            'product_voor_moeren' => 'Voor moeren',
            'product_voor_nominale_breedte' => 'Voor nominale breedte',
            'product_voor_' => 'Voor Ã˜',
            'product_voor_overstek' => 'voor overstek',
            'product_voor_raildikte_max' => 'Voor raildikte max.',
            'product_voor_schroef_' => 'Voor schroef-Ã˜',
            'product_voor_schroeven' => 'voor schroeven',
            'product_voor_sleutelwijdte' => 'Voor sleutelwijdte',
            'product_weergave' => 'Weergave',
            'product_weerstand' => 'Weerstand',
            'product_werkbereik' => 'Werkbereik',
            'product_werkbereik_' => 'Werkbereik Ã˜',
            'product_werktemperatuur' => 'Werktemperatuur',
            'product_wgnr' => 'WGNr.',
            'product_wisselspanning' => 'Wisselspanning',
            'product_wisselstroom' => 'Wisselstroom',
            'product_wordt_geleverd_in' => 'Wordt geleverd in',
            'product_zachte_draad' => 'Zachte draad',
            'product_zoninstraling' =>'Zoninstraling',
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
            $attributeGroupName = 'Handgereedschap';
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