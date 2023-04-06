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

class AddSchuurAttributes implements DataPatchInterface
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
        $attributeGroup = 'Schuurmiddelen';

        /**
         * TODO: CSV header columns
         * product_aanbev_toerental,product_aantal_pads,product_aantal_segmenten,product_afm_l_x_b,product_afm_l_x_b_x_h,product_afmetingen,product_afmetingen_l_x_b_x_h,product_africhtkrans_,product_africhtvlak,product_aluminium,product_benaming,product_binnen_,product_boring_,product_breedte_deklaag,product_buiten_,product_concentratie,product_diamantgr_en_karaatgew_ca,product_diamantinzet_,product_dikte,product_dikte_deklaag,product_ferriet,product_gereedschapsboring_,product_gereedschapshouder,product_gietijzer,product_gipsplaat,product_glas,product_grootste_kop_,product_grootste_,product_grootte,product_hardheid,product_hardheid_vilt,product_hardmetaal,product_hoek,product_hout,product_inhoud,product_keramiek,product_kleinste_kop_,product_kleur,product_komhoogte,product_kop_,product_kop__x_hoogte,product_koplengte,product_korrel,product_korrelgrootte,product_korreling_conf_us_standaard,product_korrelsoort,product_korrelverdeling,product_kunststofgvk,product_lak,product_lameldikte,product_max_spindel_,product_max_toerental,product_merk,product_metaal_hittebestendig,product_non_ferrometaal,product_non_ferrometaal_hard,product_non_ferrometaal_zacht,product_,product__mm,product__x_boring,product__x_breedte_x_boring,product__x_dikte,product__x_hoogte,product_opname,product_opspanbare_schijven,product_rolbreedte,product_rolbreedte_x_rol_,product_rvs,product_schacht,product_schacht_,product_schacht__x_lengte,product_schachtopname_,product_schotelvorm,product_schroefdraad,product_segmentbreedte,product_spaanbreker,product_spanbereik,product_spatel,product_staal,product_steen,product_straal,product_superlegering,product_tekstuitbreiding_lang,product_totale_lengte,product_type,product_uitsparing,product_uitvoering,product_verflak,product_voor_borings_,product_voor_hoeklasbreedte,product_voor_schacht_,product_voor_schijven_,product_voor_slijpschijven__tot,product_vorm,product_wanddikte,product_werkbreedte,product_werklengte,product_wgnr,product_winkelhaak
         */
        $attributeCodes = [
            'product_aanbev_toerental' => 'Aanbev. toerental',
            'product_aantal_pads' => 'Aantal pads',
            'product_aantal_segmenten' => 'Aantal segmenten',
            'product_afm_l_x_b' => 'Afm. l x b',
            'product_afm_l_x_b_x_h' => 'Afm. l x b x h',
            'product_afmetingen' => 'Afmetingen',
            'product_afmetingen_l_x_b_x_h' => 'Afmetingen l x b x h',
            'product_africhtkrans_' => 'Africhtkrans-Ã˜',
            'product_africhtvlak' => 'Africhtvlak',
            'product_aluminium' => 'Aluminium',
            'product_benaming' => 'Benaming',
            'product_binnen_' => 'Binnen-Ã˜',
            'product_boring_' => 'Boring-Ã˜',
            'product_breedte_deklaag' => 'Breedte deklaag',
            'product_buiten_' => 'Buiten-Ã˜',
            'product_concentratie' => 'Concentratie',
            'product_diamantgr_en_karaatgew_ca' => 'Diamantgr. en karaatgew. ca.',
            'product_diamantinzet_' => 'Diamantinzet-Ã˜',
            'product_dikte' => 'Dikte',
            'product_dikte_deklaag' => 'Dikte deklaag',
            'product_ferriet' => 'Ferriet',
            'product_gereedschapsboring_' => 'Gereedschapsboring-Ã˜',
            'product_gereedschapshouder' => 'Gereedschapshouder',
            'product_gietijzer' => 'Gietijzer',
            'product_gipsplaat' => 'Gipsplaat',
            'product_glas' => 'Glas',
            'product_grootste_kop_' => 'Grootste kop-Ã˜',
            'product_grootste_' => 'Grootste Ã˜',
            'product_grootte' => 'Grootte',
            'product_hardheid' => 'Hardheid',
            'product_hardheid_vilt' => 'Hardheid vilt',
            'product_hardmetaal' => 'Hardmetaal',
            'product_hoek' => 'Hoek',
            'product_hout' => 'Hout',
            'product_inhoud' => 'Inhoud',
            'product_keramiek' => 'Keramiek',
            'product_kleinste_kop_' => 'Kleinste kop-Ã˜',
            'product_kleur' => 'Kleur',
            'product_komhoogte' => 'Komhoogte',
            'product_kop_' => 'Kop-Ã˜',
            'product_kop__x_hoogte' => 'Kop-Ã˜ x hoogte',
            'product_koplengte' => 'Koplengte',
            'product_korrel' => 'Korrel',
            'product_korrelgrootte' => 'Korrelgrootte',
            'product_korreling_conf_us_standaard' => 'Korreling conf. US-standaard',
            'product_korrelsoort' => 'Korrelsoort',
            'product_korrelverdeling' => 'Korrelverdeling',
            'product_kunststofgvk' => 'Kunststof/GVK',
            'product_lak' => 'Lak',
            'product_lameldikte' => 'Lameldikte',
            'product_max_spindel_' => 'Max. spindel-Ã˜',
            'product_max_toerental' => 'Max. toerental',
            'product_merk' => 'Merk',
            'product_metaal_hittebestendig' => 'Metaal, hittebestendig',
            'product_non_ferrometaal' => 'Non-ferrometaal',
            'product_non_ferrometaal_hard' => 'Non-ferrometaal, hard',
            'product_non_ferrometaal_zacht' => 'Non-ferrometaal, zacht',
            'product_' => 'Ã˜',
            'product__mm' => 'Ã˜ mm',
            'product__x_boring' => 'Ã˜ x boring',
            'product__x_breedte_x_boring' => 'Ã˜ x breedte x boring',
            'product__x_dikte' => 'Ã˜ x dikte',
            'product__x_hoogte' => 'Ã˜ x hoogte',
            'product_opname' => 'Opname',
            'product_opspanbare_schijven' => 'Opspanbare schijven',
            'product_rolbreedte' => 'Rolbreedte',
            'product_rolbreedte_x_rol_' => 'Rolbreedte x rol-Ã˜',
            'product_rvs' => 'RVS',
            'product_schacht' => 'Schacht',
            'product_schacht_' => 'Schacht-Ã˜',
            'product_schacht__x_lengte' => 'Schacht-Ã˜ x lengte',
            'product_schachtopname_' => 'Schachtopname-Ã˜',
            'product_schotelvorm' => 'Schotelvorm',
            'product_schroefdraad' => 'Schroefdraad',
            'product_segmentbreedte' => 'Segmentbreedte',
            'product_spaanbreker' => 'Spaanbreker',
            'product_spanbereik' => 'Spanbereik',
            'product_spatel' => 'Spatel',
            'product_staal' => 'Staal',
            'product_steen' => 'Steen',
            'product_straal' => 'Straal',
            'product_superlegering' => 'Superlegering',
            'product_tekstuitbreiding_lang' => 'Tekstuitbreiding lang',
            'product_totale_lengte' => 'Totale lengte',
            'product_type' => 'Type',
            'product_uitsparing' => 'uitsparing',
            'product_uitvoering' => 'Uitvoering',
            'product_verflak' => 'Verf/lak',
            'product_voor_borings_' => 'Voor borings-Ã˜',
            'product_voor_hoeklasbreedte' => 'Voor hoeklasbreedte',
            'product_voor_schacht_' => 'Voor schacht-Ã˜',
            'product_voor_schijven_' => 'voor schijven-Ã˜',
            'product_voor_slijpschijven__tot' => 'Voor slijpschijven-Ã˜ tot',
            'product_vorm' => 'Vorm',
            'product_wanddikte' => 'Wanddikte',
            'product_werkbreedte' => 'Werkbreedte',
            'product_werklengte' => 'Werklengte',
            'product_wgnr' => 'WGNr.',
            'product_winkelhaak' => 'Winkelhaak',
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
            $attributeGroupName = 'Schuurmiddelen';
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