<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\AmbulatorySurgicalCentersRecoveryCareCenters\AmbulatorySurgicalCenterMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ControlledSubstancesPractitionersLabsManufacturers\ControlledSubstanceLaboratoriesMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ControlledSubstancesPractitionersLabsManufacturers\ManufacturersDrugsCosmeticsMedicalDevicesMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\HealthcarePractitioners\PhysicianSurgeonMdDoMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\HealthcarePractitioners\PodiatristAdvancedAnkleSurgeryPermitMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\HealthcarePractitioners\PodiatristMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\HealthcarePractitioners\PodiatristStandardAnkleSurgeryPermitMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\HealthcarePractitioners\RadiographerMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\HealthcarePractitioners\RegisteredNurseMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\HealthcarePractitioners\RespiratoryCarePractitionerMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\HealthcarePractitioners\SpeechAndLanguagePathologistMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\HealthcarePractitioners\VeterinarianMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\HemodialysisCenters\HemodialysisCentersMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\Hospitals\ChildrensHospitalsMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\Hospitals\ChronicDiseaseHospitalsMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\Hospitals\GeneralHospitalsMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\Hospitals\HospitalsForMentallyIllPersonsMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\Hospitals\MaternityHospitalsMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\InfirmariesClinics\FamilyPlanningClinicsMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\InfirmariesClinics\InfirmaryOperatedByAnEducationalInstitutionMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\InfirmariesClinics\OutpatientClinicMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\InfirmariesClinics\WllChildClinicMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\LongTermCareAssistedLivingFacilitiesHomeHealthCareHospice\AssistedLivingServiceAgencyMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\LongTermCareAssistedLivingFacilitiesHomeHealthCareHospice\ChronicAndConvalescentNursingHomeMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\LongTermCareAssistedLivingFacilitiesHomeHealthCareHospice\ChronicAndConvalescentNursingHomesAndRestHomesWithNursingSupervisionMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\LongTermCareAssistedLivingFacilitiesHomeHealthCareHospice\HomeHealthCareAgenciesMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\LongTermCareAssistedLivingFacilitiesHomeHealthCareHospice\HomemakerHomeHealthCareMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\LongTermCareAssistedLivingFacilitiesHomeHealthCareHospice\HospiceCareMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\LongTermCareAssistedLivingFacilitiesHomeHealthCareHospice\NursingHomeAdministratorMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\LongTermCareAssistedLivingFacilitiesHomeHealthCareHospice\NursingHomeManagementCompanyMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\LongTermCareAssistedLivingFacilitiesHomeHealthCareHospice\ResidentialCareFacilityMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\LongTermCareAssistedLivingFacilitiesHomeHealthCareHospice\RestHomesWithNursingSupervisionMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MedicalMarijuanaProducerDispensaryFacilityDispensaryEtc\MedicalMarijuanaDispensaryFacilityBackerLicenseMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MedicalMarijuanaProducerDispensaryFacilityDispensaryEtc\MedicalMarijuanaDispensaryFacilityEmployeeLicenseMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MedicalMarijuanaProducerDispensaryFacilityDispensaryEtc\MedicalMarijuanaDispensaryFacilityLicenseMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MedicalMarijuanaProducerDispensaryFacilityDispensaryEtc\MedicalMarijuanaDispensaryLicenseMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MedicalMarijuanaProducerDispensaryFacilityDispensaryEtc\MedicalMarijuanaDispensaryTechnicianLicenseMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MedicalMarijuanaProducerDispensaryFacilityDispensaryEtc\MedicalMarijuanaProducerBackerLicenseMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MedicalMarijuanaProducerDispensaryFacilityDispensaryEtc\MedicalMarijuanaProducerEmployeeLicenseMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MedicalMarijuanaProducerDispensaryFacilityDispensaryEtc\MedicalMarijuanaProducerLicenseMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MentalHealthCare\ClinicalSocialWorkerMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MentalHealthCare\HospitalsForMentallyIllPersonsMapper as HospitalsForMentallyIllPersonsMapper2;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MentalHealthCare\MaritalAndFamilyTherapistMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MentalHealthCare\MasterSLevelSocialWorkerMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MentalHealthCare\MentalHealthCommunityResidenceMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MentalHealthCare\MentalHealthDayTreatementFacilitiesMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MentalHealthCare\MentalHealthResidentialLivingFacilitiesMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MentalHealthCare\ProfessionalCounselorMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MentalHealthCare\PsychiratricOutpatientClinicsMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MentalHealthCare\PsychologistMapper;


class MapperFactory
{
	protected static $classes = [
        'ambulatory_surgical_centers_recovery_care_centers' => [
            'ambulatory_surgical_center' => AmbulatorySurgicalCenterMapper::class
        ],
        'controlled_substances_practitioners_labs_manufacturers' => [
            'controlled_substance_laboratories' => ControlledSubstanceLaboratoriesMapper::class,
            'manufacturers_of_drugs_cosmetics_and_medical_devices' => ManufacturersDrugsCosmeticsMedicalDevicesMapper::class
        ],
        'healthcare_practitioners' => [
            'acupuncturist' => PodiatristMapper::class,
            'physician_surgeon_md_do' => PhysicianSurgeonMdDoMapper::class,
            'podiatrist' => PodiatristMapper::class,
            'podiatrist_advanced_ankle_surgery_permit' => PodiatristAdvancedAnkleSurgeryPermitMapper::class,
            'podiatrist_standard_ankle_surgery_permit' => PodiatristStandardAnkleSurgeryPermitMapper::class,
            'radiographer' => RadiographerMapper::class,
            'registered_nurse' => RegisteredNurseMapper::class,
            'respiratory_care_practitioner' => RespiratoryCarePractitionerMapper::class,
            'speech_and_language_pathologist' => SpeechAndLanguagePathologistMapper::class,
            'veterinarian' => VeterinarianMapper::class,
        ],
        'hemodialysis_centers' => [
            'hemodialysis_centers' => HemodialysisCentersMapper::class,
        ],
        'hospitals' => [
            'childrens_hospitals' => ChildrensHospitalsMapper::class,
            'chronic_disease_hospitals' => ChronicDiseaseHospitalsMapper::class,
            'general_hospitals' => GeneralHospitalsMapper::class,
            'hospitals_for_mentally_ill_persons' => HospitalsForMentallyIllPersonsMapper::class,
            'maternity_hospitals' => MaternityHospitalsMapper::class,
        ],
        'infirmaries_clinics' => [
            'family_planning_clinics' => FamilyPlanningClinicsMapper::class,
            'infirmary_operated_by_an_educational_institution' => InfirmaryOperatedByAnEducationalInstitutionMapper::class,
            'outpatient_clinic' => OutpatientClinicMapper::class,
            'wll_child_clinic' => WllChildClinicMapper::class
        ],
        'long_term_care_assisted_living_facilities_home_health_care_hospice' => [
            'assisted_living_service_agency' => AssistedLivingServiceAgencyMapper::class,
            'chronic_and_convalescent_nursing_home' => ChronicAndConvalescentNursingHomeMapper::class,
            'chronic_and_convalescent_nursing_homes_and_rest_homes_with_nursing_supervision' => ChronicAndConvalescentNursingHomesAndRestHomesWithNursingSupervisionMapper::class,
            'home_health_care_agencies' => HomeHealthCareAgenciesMapper::class,
            'homemaker_home_health_care' => HomemakerHomeHealthCareMapper::class,
            'hospice_care' => HospiceCareMapper::class,
            'nursing_home_administrator' => NursingHomeAdministratorMapper::class,
            'nursing_home_management_company' => NursingHomeManagementCompanyMapper::class,
            'residential_care_facility' => ResidentialCareFacilityMapper::class,
            'rest_homes_with_nursing_supervision' => RestHomesWithNursingSupervisionMapper::class
        ],
        'medical_marijuana_producer_dispensary_facility_dispensary_etc' => [
            'medical_marijuana_dispensary_facility_backer_license' => MedicalMarijuanaDispensaryFacilityBackerLicenseMapper::class,
            'medical_marijuana_dispensary_facility_employee_license' => MedicalMarijuanaDispensaryFacilityEmployeeLicenseMapper::class,
            'medical_marijuana_dispensary_facility_license' => MedicalMarijuanaDispensaryFacilityLicenseMapper::class,
            'medical_marijuana_dispensary_license' => MedicalMarijuanaDispensaryLicenseMapper::class,
            'medical_marijuana_dispensary_technician_license' => MedicalMarijuanaDispensaryTechnicianLicenseMapper::class,
            'medical_marijuana_producer_backer_license' => MedicalMarijuanaProducerBackerLicenseMapper::class,
            'medical_marijuana_producer_employee_license' => MedicalMarijuanaProducerEmployeeLicenseMapper::class,
            'medical_marijuana_producer_license' => MedicalMarijuanaProducerLicenseMapper::class,
        ],
        'mental_health_care' => [
            'clinical_social_worker' => ClinicalSocialWorkerMapper::class,
            'hospitals_for_mentally_ill_persons' => HospitalsForMentallyIllPersonsMapper2::class,
            'marital_and_family_therapist' => MaritalAndFamilyTherapistMapper::class,
            'master_s_level_social_worker' => MasterSLevelSocialWorkerMapper::class,
            'mental_health_community_residence' => MentalHealthCommunityResidenceMapper::class,
            'mental_health_day_treatement_facilities' => MentalHealthDayTreatementFacilitiesMapper::class,
            'mental_health_residential_living_facilities' => MentalHealthResidentialLivingFacilitiesMapper::class,
            'professional_counselor' => ProfessionalCounselorMapper::class,
            'psychiratric_outpatient_clinics' => PsychiratricOutpatientClinicsMapper::class,
            'psychologist' => PsychologistMapper::class,
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