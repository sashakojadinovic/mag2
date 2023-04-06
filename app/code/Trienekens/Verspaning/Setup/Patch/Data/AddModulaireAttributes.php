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

class AddModulaireAttributes implements DataPatchInterface
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
        $attributeGroup = 'Modulaire Verspaning';

        /**
         * TODO: CSV header columns
         * product_a_005,product_aantal_wisselplaten,product_aantal_zc,product_ap_max,product_b,product_benaming,product_benodigde_kartels,product_bladbreedte,product_bladhoogte,product_boorgat,product_boorgat_,product_breedte,product_breedte_006,product_brootskracht,product_brootslengte,product_brootslengte_max,product_brootslengte_min,product_d_min,product_dikte,product_doorgangen,product_exacte_maat_k,product_f,product_gereedschap,product_grootte,product_h,product_h_hard,product_h_hard_snijdiepte,product_h_hard_toevoer,product_h_x_b,product_h1,product_h13h14,product_hoogtebreedte_x_lengte,product_inlegstukken,product_iso_omschrijving,product_k,product_k_gietijzer,product_k_gietijzer_snijdiepte,product_k_gietijzer_toevoer,product_kartel_,product_kleinste_boor_,product_kleinste_boor__rond,product_kleinste_boor__vierkant,product_kopbreedte,product_kr,product_l,product_l1,product_l2,product_l3,product_lengte,product_m_roestvast,product_m_roestvast_snijdiepte,product_m_roestvast_toevoer,product_maat_diagonaal,product_maat_houder,product_maat___h6,product_maat___h7,product_max_gereedschap_,product_model,product_n_aluminium,product_n_aluminium_snijdiepte,product_n_aluminium_toevoer,product__d,product__d_g7,product__d_h6,product__d_h7,product__d_max,product__d_min,product__d1,product__d2,product__h7,product__h8,product_p_staal,product_p_staal_snijdiepte,product_p_staal_toevoer,product_radiale_dispositie_versch__,product_radius_r,product_rugbreedte_x_lengte,product_s,product_s_superleg_snijdiepte,product_s_superleg_toevoer,product_s_superlegering,product_schacht,product_schacht_,product_schacht_rond_massief_dg7,product_schacht_rondmassief_dg7_mm,product_schacht_rundvollmaterial,product_schacht_vierkant,product_schachtlengte,product_sleutelwijdte,product_snijsnelheid_voeding,product_snijvlaklengte,product_soort_snijmateriaal,product_spiebaanbreedte,product_spoed,product_steek,product_steekbreedte,product_steekbreedte_rond_massief,product_steekbreedtevierkant,product_steekwisselplaat,product_tekstuitbreiding_lang,product_tmax,product_tolerantie_js9,product_totale_lengte,product_totale_lengte_rond_massief,product_totale_lengte_vierkant,product_uitgang_boorgat_,product_uitvoering,product_v_spiegrootte,product_voor_kartelrol,product_voor_schacht,product_voor_schacht_,product_voor_schroeven,product_voor_steekblad,product_vrije_hoek,product_w,product_werkbereik_,product_wgnr,product_wisselplaat,product_z,product_zc
         */
        $attributeCodes = [
            'product_a_005' => 'a Â±0,05',
            'product_aantal_wisselplaten' => 'Aantal wisselplaten',
            'product_aantal_zc' => 'Aantal zc*',
            'product_ap_max' => 'ap max.',
            'product_b' => 'b',
            'product_benaming' => 'Benaming',
            'product_benodigde_kartels' => 'Benodigde kartels',
            'product_bladbreedte' => 'Bladbreedte',
            'product_bladhoogte' => 'Bladhoogte',
            'product_boorgat' => 'Boorgat',
            'product_boorgat_' => 'Boorgat-Ã˜',
            'product_breedte' => 'Breedte',
            'product_breedte_006' => 'Breedte Â±0,06',
            'product_brootskracht' => 'Brootskracht',
            'product_brootslengte' => 'Brootslengte',
            'product_brootslengte_max' => 'Brootslengte max.',
            'product_brootslengte_min' => 'Brootslengte min.',
            'product_d_min' => 'D min.',
            'product_dikte' => 'Dikte',
            'product_doorgangen' => 'Doorgangen',
            'product_exacte_maat_k' => 'Exacte maat K',
            'product_f' => 'F',
            'product_gereedschap' => 'Gereedschap',
            'product_grootte' => 'Grootte',
            'product_h' => 'h',
            'product_h_hard' => 'H hard',
            'product_h_hard_snijdiepte' => 'H hard-snijdiepte',
            'product_h_hard_toevoer' => 'H hard-toevoer',
            'product_h_x_b' => 'h x b',
            'product_h1' => 'h1',
            'product_h13h14' => 'h13/h14',
            'product_hoogtebreedte_x_lengte' => 'Hoogte/breedte x lengte',
            'product_inlegstukken' => 'Inlegstukken',
            'product_iso_omschrijving' => 'ISO-omschrijving',
            'product_k' => 'K',
            'product_k_gietijzer' => 'K gietijzer',
            'product_k_gietijzer_snijdiepte' => 'K gietijzer-snijdiepte',
            'product_k_gietijzer_toevoer' => 'K gietijzer-toevoer',
            'product_kartel_' => 'Kartel-Ã˜',
            'product_kleinste_boor_' => 'Kleinste boor-Ã˜',
            'product_kleinste_boor__rond' => 'Kleinste boor-Ã˜ rond',
            'product_kleinste_boor__vierkant' => 'Kleinste boor-Ã˜ vierkant',
            'product_kopbreedte' => 'Kopbreedte',
            'product_kr' => 'Kr',
            'product_l' => 'L',
            'product_l1' => 'L1',
            'product_l2' => 'l2',
            'product_l3' => 'L3',
            'product_lengte' => 'Lengte',
            'product_m_roestvast' => 'M roestvast',
            'product_m_roestvast_snijdiepte' => 'M roestvast-snijdiepte',
            'product_m_roestvast_toevoer' => 'M roestvast-toevoer',
            'product_maat_diagonaal' => 'Maat diagonaal',
            'product_maat_houder' => 'Maat houder',
            'product_maat___h6' => 'Maat = Ã˜ h6',
            'product_maat___h7' => 'Maat = Ã˜ h7',
            'product_max_gereedschap_' => 'max. gereedschap-Ã˜',
            'product_model' => 'Model',
            'product_n_aluminium' => 'N aluminium',
            'product_n_aluminium_snijdiepte' => 'N aluminium-snijdiepte',
            'product_n_aluminium_toevoer' => 'N aluminium-toevoer',
            'product__d' => 'Ã˜ D',
            'product__d_g7' => 'Ã˜ d g7',
            'product__d_h6' => 'Ã˜ d h6',
            'product__d_h7' => 'Ã˜ d H7',
            'product__d_max' => 'Ã˜ D max.',
            'product__d_min' => 'Ã˜ D min.',
            'product__d1' => 'Ã˜ D1',
            'product__d2' => 'Ã˜ D2',
            'product__h7' => 'Ã˜ H7',
            'product__h8' => 'Ã˜ h8',
            'product_p_staal' => 'P staal',
            'product_p_staal_snijdiepte' => 'P staal-snijdiepte',
            'product_p_staal_toevoer' => 'P staal-toevoer',
            'product_radiale_dispositie_versch__' => 'Radiale dispositie versch. -',
            'product_radius_r' => 'Radius (r)',
            'product_rugbreedte_x_lengte' => 'Rugbreedte x lengte',
            'product_s' => 's',
            'product_s_superleg_snijdiepte' => 'S superleg.-snijdiepte',
            'product_s_superleg_toevoer' => 'S Superleg.-toevoer',
            'product_s_superlegering' => 'S superlegering',
            'product_schacht' => 'schacht',
            'product_schacht_' => 'Schacht-Ã˜',
            'product_schacht_rond_massief_dg7' => 'Schacht-rond (massief) dg7',
            'product_schacht_rondmassief_dg7_mm' => 'Schacht-rond(massief) dg7 mm',
            'product_schacht_rundvollmaterial' => 'Schacht-rund(Vollmaterial)',
            'product_schacht_vierkant' => 'Schacht-vierkant',
            'product_schachtlengte' => 'Schachtlengte',
            'product_sleutelwijdte' => 'Sleutelwijdte',
            'product_snijsnelheid_voeding' => 'Snijsnelheid Voeding',
            'product_snijvlaklengte' => 'Snijvlaklengte',
            'product_soort_snijmateriaal' => 'Soort snijmateriaal',
            'product_spiebaanbreedte' => 'Spiebaanbreedte',
            'product_spoed' => 'Spoed',
            'product_steek' => 'Steek',
            'product_steekbreedte' => 'Steekbreedte',
            'product_steekbreedte_rond_massief' => 'Steekbreedte rond (massief)',
            'product_steekbreedtevierkant' => 'Steekbreedtevierkant',
            'product_steekwisselplaat' => 'Steekwisselplaat',
            'product_tekstuitbreiding_lang' => 'Tekstuitbreiding lang',
            'product_tmax' => 'tmax',
            'product_tolerantie_js9' => 'Tolerantie JS9',
            'product_totale_lengte' => 'Totale lengte',
            'product_totale_lengte_rond_massief' => 'Totale lengte rond (massief)',
            'product_totale_lengte_vierkant' => 'Totale lengte vierkant',
            'product_uitgang_boorgat_' => 'Uitgang-boorgat Ã˜',
            'product_uitvoering' => 'Uitvoering',
            'product_v_spiegrootte' => 'v. spiegrootte',
            'product_voor_kartelrol' => 'voor kartelrol',
            'product_voor_schacht' => 'voor schacht',
            'product_voor_schacht_' => 'Voor schacht-Ã˜',
            'product_voor_schroeven' => 'voor schroeven',
            'product_voor_steekblad' => 'voor steekblad',
            'product_vrije_hoek' => 'Vrije hoek',
            'product_w' => 'W',
            'product_werkbereik_' => 'Werkbereik Ã˜',
            'product_wgnr' => 'WGNr.',
            'product_wisselplaat' => 'Wisselplaat',
            'product_z' => 'Z',
            'product_zc' => 'zc*',
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
            $attributeGroupName = 'Modulaire Verspaning';
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