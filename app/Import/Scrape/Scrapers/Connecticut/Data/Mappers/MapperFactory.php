<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\AmbulatorySurgicalCentersRecoveryCareCenters\AmbulatorySurgicalCenterMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ChildDayCareLicensingProgram\ChildDayCareCentersAndGroupDayCareHomesClosedOneYearMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ChildDayCareLicensingProgram\ChildDayCareCentersAndGroupDayCareHomesOpenedOneYearMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ChildDayCareLicensingProgram\ChildDayCareCentersAndGroupDayCareHomesTotalByDateActiveMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ChildDayCareLicensingProgram\FamilyDayCareHomesClosedOneYearMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ChildDayCareLicensingProgram\FamilyDayCareHomesOpenedOneYearMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ChildDayCareLicensingProgram\FamilyDayCareHomesTotalByDateActiveMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ControlledSubstancesPractitionersLabsManufacturers\ControlledSubstanceLaboratoriesMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ControlledSubstancesPractitionersLabsManufacturers\ControlledSubstanceRegistrationForPractitionersMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ControlledSubstancesPractitionersLabsManufacturers\ManufacturersDrugsCosmeticsMedicalDevicesMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ControlledSubstancesPractitionersLabsManufacturers\WholesalersOfDrugsCosmeticsAndMedicalDevicesMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\DrugControlPharmacyPharmacistsEtc\DealersOfElectronicNicotineDeliverySystemsOrVaporProductsMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\DrugControlPharmacyPharmacistsEtc\NonLegendDrugPermitsMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\DrugControlPharmacyPharmacistsEtc\NonResidentPharmaciesMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\DrugControlPharmacyPharmacistsEtc\PharmaciesMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\DrugControlPharmacyPharmacistsEtc\PharmacistsMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\DrugControlPharmacyPharmacistsEtc\PharmacyTechniciansMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\DrugControlPharmacyPharmacistsEtc\TemporaryPermitToPracticePharmacyMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\EmergencyMedicalServices\AdvancedEmergencyMedicalTechnicianMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\EmergencyMedicalServices\CertifiedEmsOrganizationMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\EmergencyMedicalServices\ParamedicMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\HealthcarePractitioners\OccupationalTherapistMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\HealthcarePractitioners\PhysicalTherapistMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\HealthcarePractitioners\PhysicianSurgeonMdDoMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\HealthcarePractitioners\PodiatristMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\HemodialysisCenters\HemodialysisCentersMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\Hospitals\ChildrensHospitalsMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\InfirmariesClinics\FamilyPlanningClinicsMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\LongTermCareAssistedLivingFacilitiesHomeHealthCareHospice\AssistedLivingServiceAgencyMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\LongTermCareAssistedLivingFacilitiesHomeHealthCareHospice\NursingHomeAdministratorMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MedicalMarijuanaProducerDispensaryFacilityDispensaryEtc\MedicalMarijuanaDispensaryFacilityBackerLicenseMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MedicalMarijuanaProducerDispensaryFacilityDispensaryEtc\MedicalMarijuanaDispensaryFacilityLicenseMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MentalHealthCare\ClinicalSocialWorkerMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MentalHealthCare\HospitalsForMentallyIllPersonsMapper as HospitalsForMentallyIllPersonsMapper2;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\RegisteredSanitarian\RegisteredSanitarianMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\SubstanceAbuseCare\CertifiedAlcoholAndDrugCounselorMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\SubstanceAbuseCare\SubstanceAbuseCareFacilitesMapper;


class MapperFactory
{
	protected static $classes = [
        'ambulatory_surgical_centers_recovery_care_centers' => [
            'ambulatory_surgical_center' => AmbulatorySurgicalCenterMapper::class
        ],
        'controlled_substances_practitioners_labs_manufacturers' => [
            'controlled_substance_laboratories' => ControlledSubstanceLaboratoriesMapper::class,
            'controlled_substance_registration_for_practitioners' => ControlledSubstanceRegistrationForPractitionersMapper::class,
            'manufacturers_of_drugs_cosmetics_and_medical_devices' => ManufacturersDrugsCosmeticsMedicalDevicesMapper::class,
            'wholesalers_of_drugs_cosmetics_and_medical_devices' => WholesalersOfDrugsCosmeticsAndMedicalDevicesMapper::class
        ],
	    'child_day_care_licensing_program' => [
	        'child_day_care_centers_and_group_day_care_homes_closed_1_year' => ChildDayCareCentersAndGroupDayCareHomesClosedOneYearMapper::class,
	        'child_day_care_centers_and_group_day_care_homes_closed_60_days' => ChildDayCareCentersAndGroupDayCareHomesClosedOneYearMapper::class,
	        'child_day_care_centers_and_group_day_care_homes_opened_1_year' => ChildDayCareCentersAndGroupDayCareHomesOpenedOneYearMapper::class,
	        'child_day_care_centers_and_group_day_care_homes_opened_60_days' => ChildDayCareCentersAndGroupDayCareHomesOpenedOneYearMapper::class,
	        'child_day_care_centers_and_group_day_care_homes_total_by_date_active' => ChildDayCareCentersAndGroupDayCareHomesTotalByDateActiveMapper::class,
	        'family_day_care_homes_closed_1_year' => FamilyDayCareHomesClosedOneYearMapper::class,
	        'family_day_care_homes_closed_60_days' => FamilyDayCareHomesClosedOneYearMapper::class,
	        'family_day_care_homes_opened_1_year' => FamilyDayCareHomesOpenedOneYearMapper::class,
	        'family_day_care_homes_opened_60_days' => FamilyDayCareHomesOpenedOneYearMapper::class,
	        'family_day_care_homes_total_by_date_active' => FamilyDayCareHomesTotalByDateActiveMapper::class
	    ],
	    'drug_control_pharmacy_pharmacists_etc' => [
            'dealers_of_electronic_nicotine_delivery_systems_or_vapor_products' => DealersOfElectronicNicotineDeliverySystemsOrVaporProductsMapper::class,
	        'non_legend_drug_permits' => NonLegendDrugPermitsMapper::class,
	        'non_resident_pharmacies' => NonResidentPharmaciesMapper::class,
	        'pharmacies' => PharmaciesMapper::class,
	        'pharmacists' => PharmacistsMapper::class,
	        'pharmacy_interns' => PharmacistsMapper::class,
	        'pharmacy_technicians' => PharmacyTechniciansMapper::class,
	        'temporary_permit_to_practice_pharmacy' => TemporaryPermitToPracticePharmacyMapper::class
	    ],
        'emergency_medical_services' => [
            'advanced_emergency_medical_technician' => AdvancedEmergencyMedicalTechnicianMapper::class,
            'certified_ems_organization' => CertifiedEmsOrganizationMapper::class,
            'emergency_medical_responder' => AdvancedEmergencyMedicalTechnicianMapper::class,
            'emergency_medical_services_instructor' => AdvancedEmergencyMedicalTechnicianMapper::class,
            'emergency_medical_technician' => AdvancedEmergencyMedicalTechnicianMapper::class,
            'first_responder_organization' => CertifiedEmsOrganizationMapper::class,
            'licensed_ems_organization' => CertifiedEmsOrganizationMapper::class,
            'paramedic' => ParamedicMapper::class,
            'supplemental_responder_ems_organization' => CertifiedEmsOrganizationMapper::class
        ],
        'healthcare_practitioners' => [
            'advanced_practice_registered_nurse' => PhysicalTherapistMapper::class,
            'athletic_trainer' => PodiatristMapper::class,
            'audiologist' => PodiatristMapper::class,
            'chiropractor' => PodiatristMapper::class,
            'dental_anesthesia_conscious_sedation_permit' => PhysicalTherapistMapper::class,
            'dental_conscious_sedation_permit' => PhysicalTherapistMapper::class,
            'dental_hygienist' => PhysicalTherapistMapper::class,
            'dentist' => PodiatristMapper::class,
            'dietitian_nutritionist' => PodiatristMapper::class,
            'genetic_counselor' => PodiatristMapper::class,
            'hearing_instrument_specialist' => PodiatristMapper::class,
            'homeopathic_physician' => PodiatristMapper::class,
            'licensed_practical_nurse' => PodiatristMapper::class,
            'massage_therapist' => PodiatristMapper::class,
            'naturopathic_physician' => PodiatristMapper::class,
            'nurse_midwife' => PodiatristMapper::class,
            'occupational_therapist' => OccupationalTherapistMapper::class,
            'occupational_therapist_assistant' => PodiatristMapper::class,
            'optician' => PodiatristMapper::class,
            'optician_apprentice' => PodiatristMapper::class,
            'optometrist' => PodiatristMapper::class,
            'physical_therapist' => PhysicalTherapistMapper::class,
            'physical_therapist_assistant' => PodiatristMapper::class,
            'physician_assistant' => PodiatristMapper::class,
            'acupuncturist' => PodiatristMapper::class,
            'physician_surgeon_md_do' => PhysicianSurgeonMdDoMapper::class,
            'podiatrist' => PodiatristMapper::class,
            'podiatrist_advanced_ankle_surgery_permit' => PodiatristMapper::class,
            'podiatrist_standard_ankle_surgery_permit' => PodiatristMapper::class,
            'radiographer' => PodiatristMapper::class,
            'registered_nurse' => PodiatristMapper::class,
            'respiratory_care_practitioner' => PodiatristMapper::class,
            'speech_and_language_pathologist' => PodiatristMapper::class,
            'veterinarian' => PodiatristMapper::class,
        ],
        'hemodialysis_centers' => [
            'hemodialysis_centers' => HemodialysisCentersMapper::class,
        ],
        'hospitals' => [
            'childrens_hospitals' => ChildrensHospitalsMapper::class,
            'chronic_disease_hospitals' => ChildrensHospitalsMapper::class,
            'general_hospitals' => ChildrensHospitalsMapper::class,
            'hospitals_for_mentally_ill_persons' => ChildrensHospitalsMapper::class,
            'maternity_hospitals' => ChildrensHospitalsMapper::class,
        ],
        'infirmaries_clinics' => [
            'family_planning_clinics' => FamilyPlanningClinicsMapper::class,
            'infirmary_operated_by_an_educational_institution' => FamilyPlanningClinicsMapper::class,
            'outpatient_clinic' => FamilyPlanningClinicsMapper::class,
            'wll_child_clinic' => FamilyPlanningClinicsMapper::class
        ],
        'long_term_care_assisted_living_facilities_home_health_care_hospice' => [
            'assisted_living_service_agency' => AssistedLivingServiceAgencyMapper::class,
            'chronic_and_convalescent_nursing_home' => AssistedLivingServiceAgencyMapper::class,
            'chronic_and_convalescent_nursing_homes_and_rest_homes_with_nursing_supervision' => AssistedLivingServiceAgencyMapper::class,
            'home_health_care_agencies' => AssistedLivingServiceAgencyMapper::class,
            'homemaker_home_health_care' => AssistedLivingServiceAgencyMapper::class,
            'hospice_care' => AssistedLivingServiceAgencyMapper::class,
            'nursing_home_administrator' => NursingHomeAdministratorMapper::class,
            'nursing_home_management_company' => AssistedLivingServiceAgencyMapper::class,
            'residential_care_facility' => AssistedLivingServiceAgencyMapper::class,
            'rest_homes_with_nursing_supervision' => AssistedLivingServiceAgencyMapper::class
        ],
        'medical_marijuana_producer_dispensary_facility_dispensary_etc' => [
            'medical_marijuana_dispensary_facility_backer_license' => MedicalMarijuanaDispensaryFacilityBackerLicenseMapper::class,
            'medical_marijuana_dispensary_facility_employee_license' => MedicalMarijuanaDispensaryFacilityBackerLicenseMapper::class,
            'medical_marijuana_dispensary_facility_license' => MedicalMarijuanaDispensaryFacilityLicenseMapper::class,
            'medical_marijuana_dispensary_license' => MedicalMarijuanaDispensaryFacilityBackerLicenseMapper::class,
            'medical_marijuana_dispensary_technician_license' => MedicalMarijuanaDispensaryFacilityBackerLicenseMapper::class,
            'medical_marijuana_producer_backer_license' => MedicalMarijuanaDispensaryFacilityBackerLicenseMapper::class,
            'medical_marijuana_producer_employee_license' => MedicalMarijuanaDispensaryFacilityBackerLicenseMapper::class,
            'medical_marijuana_producer_license' => MedicalMarijuanaDispensaryFacilityLicenseMapper::class,
        ],
        'mental_health_care' => [
            'clinical_social_worker' => ClinicalSocialWorkerMapper::class,
            'hospitals_for_mentally_ill_persons' => HospitalsForMentallyIllPersonsMapper2::class,
            'marital_and_family_therapist' => ClinicalSocialWorkerMapper::class,
            'master_s_level_social_worker' => ClinicalSocialWorkerMapper::class,
            'mental_health_community_residence' => HospitalsForMentallyIllPersonsMapper2::class,
            'mental_health_day_treatement_facilities' => HospitalsForMentallyIllPersonsMapper2::class,
            'mental_health_residential_living_facilities' => HospitalsForMentallyIllPersonsMapper2::class,
            'professional_counselor' => ClinicalSocialWorkerMapper::class,
            'psychiratric_outpatient_clinics' => HospitalsForMentallyIllPersonsMapper2::class,
            'psychologist' => ClinicalSocialWorkerMapper::class,
        ],
        'registered_sanitarians' => [
            'registered_sanitarian' => RegisteredSanitarianMapper::class
        ],
        'substance_abuse_care' => [
            'certified_alcohol_and_drug_counselor' => CertifiedAlcoholAndDrugCounselorMapper::class,
            'licensed_alcohol_and_drug_counselor' => CertifiedAlcoholAndDrugCounselorMapper::class,
            'substance_abuse_care_facilites' => SubstanceAbuseCareFacilitesMapper::class,
        ]
    ];
	
	/**
	 * Create by keys
	 * @param string $category
	 * @param string $option
	 * @return MapperInterface
	 */
	public static function createByKeys($category, $option, $csvHeaders = [])
	{
		$class = static::$classes[$category][$option];
		
		return new $class($csvHeaders);
	}
}