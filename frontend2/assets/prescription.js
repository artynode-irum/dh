let currentStep = 1;

function showStep(stepNumber) {
  document.querySelectorAll(".form-step").forEach((step) => {
    step.classList.remove("active");
  });

  const stepElement = document.getElementById(`step${stepNumber}`);
  stepElement.classList.add("active");
  stepElement.classList.add(
    "animate__animated",
    "animate__fadeIn",
    "animate__faster"
  );
}

function validateStep(stepNumber) {
  let isValid = true; // Initialize as true, will be set to false if any validation fails

  function clearErrors() {
    document.querySelectorAll(".input-error").forEach((input) => {
      input.classList.remove("input-error");
    });
  }

  clearErrors(); // Clear errors before validating

  switch (stepNumber) {
    case 1:
      // Initialize isValid as true
      // let isValid = true;

      // Get the required elements
      const treatment = document.querySelector(
        'input[name="treatment"]:checked'
      );
      const prescriptionafter = document.querySelector(
        'input[name="prescriptionafter"]:checked'
      );
      const dosage = document.querySelector('input[name="dosage"]:checked');
      const currentlyppb = document.querySelector(
        'input[name="currentlyppb"]:checked'
      );

      // Validate required fields
      if (!treatment) {
        isValid = false;
        document
          .querySelector('input[name="treatment"]')
          .classList.add("input-error");
      }
      if (!prescriptionafter) {
        isValid = false;
        document
          .querySelector('input[name="prescriptionafter"]')
          .classList.add("input-error");
      }

      if (!currentlyppb) {
        isValid = false;
        document
          .querySelector('input[name="currentlyppb"]')
          .classList.add("input-error");
      }

      // Alert if any required field is missing
      if (!isValid) {
        alert("Please complete all required fields before proceeding.");
      }
      break;

    // case 2:
    //   // Initialize isValid as true
    //   // let isValid = true;

    //   // Get the required elements

    //   const specific_medication_seeking = document.querySelector(
    //     'input[name="specific_medication_seeking"]:checked'
    //   );
    //   const known_allergies = document.querySelector(
    //     'input[name="known_allergies"]:checked'
    //   );
    //   const reason_known_allergies_yes = document.querySelector(
    //     'input[name="reason_known_allergies_yes"]'
    //   ).value;
    //   const previously_taken_medi = document.querySelector(
    //     'input[name="previously_taken_medi"]:checked'
    //   );
    //   const known_nill_allergies = document.querySelector(
    //     'input[name="known_nill_allergies"]'
    //   ).value;
    //   const health_condition = document.querySelector(
    //     'input[name="health_condition"]'
    //   ).value;
    //   const over_the_counter_drugs = document.querySelector(
    //     'input[name="over_the_counter_drugs"]'
    //   ).value;
    //   const medication_used_previously = document.querySelector(
    //     'input[name="medication_used_previously"]:checked'
    //   );
    //   const plan_schedule = document.querySelector(
    //     'input[name="plan_schedule"]:checked'
    //   );
    //   // const health_condition = document.querySelector('input[name="health_condition"]:checked');
    //   const adverse_reactions = document.querySelector(
    //     'input[name="adverse_reactions"]:checked'
    //   );
    //   // const over_the_counter_drugs = document.querySelector('input[name="over_the_counter_drugs"]:checked');
    //   const healthcare_provider_person_recently = document.querySelector(
    //     'input[name="healthcare_provider_person_recently"]:checked'
    //   );

    //   // Validate required fields

    //   if (!previously_taken_medi) {
    //     isValid = false;
    //     document
    //       .querySelector('input[name="previously_taken_medi"]')
    //       .classList.add("input-error");
    //   }

    //   // Conditional validation based on previously_taken_medi-no
    //   if (document.getElementById("previouslytakenmedino").checked) {
    //     if (!specific_medication_seeking) {
    //       isValid = false;
    //       document
    //         .querySelector('input[name="specific_medication_seeking"]')
    //         .classList.add("input-error");
    //     }
    //     if (!known_nill_allergies) {
    //       isValid = false;
    //       document
    //         .querySelector('input[name="known_nill_allergies"]')
    //         .classList.add("input-error");
    //     }
    //     if (!medication_used_previously) {
    //       isValid = false;
    //       document
    //         .querySelector('input[name="medication_used_previously"]')
    //         .classList.add("input-error");
    //     }
    //     if (!plan_schedule) {
    //       isValid = false;
    //       document
    //         .querySelector('input[name="plan_schedule"]')
    //         .classList.add("input-error");
    //     }
    //   }

    //   // Conditional validation based on previously_taken_medi-yes
    //   if (document.getElementById("previouslytakenmediyes").checked) {
    //     if (!health_condition) {
    //       isValid = false;
    //       document
    //         .querySelector('input[name="health_condition"]')
    //         .classList.add("input-error");
    //     }

    //     if (!over_the_counter_drugs) {
    //       isValid = false;
    //       document
    //         .querySelector('input[name="over_the_counter_drugs"]')
    //         .classList.add("input-error");
    //     }
    //     if (!adverse_reactions) {
    //       isValid = false;
    //       document
    //         .querySelector('input[name="adverse_reactions"]')
    //         .classList.add("input-error");
    //     }

    //     if (!healthcare_provider_person_recently) {
    //       isValid = false;
    //       document
    //         .querySelector('input[name="healthcare_provider_person_recently"]')
    //         .classList.add("input-error");
    //     }

    //     if (!known_allergies) {
    //       isValid = false;
    //       document
    //         .querySelector('input[name="known_allergies"]')
    //         .classList.add("input-error");
    //     }
    //     if (document.getElementById("known-allergies-yes").checked) {
    //       if (!reason_known_allergies_yes) {
    //         isValid = false;
    //         document
    //           .querySelector('input[name="reason_known_allergies_yes"]')
    //           .classList.add("input-error");
    //       }
    //     }

    //     // if (document.getElementById('input[id="known-allergies-yes"]') && !reason_known_allergies_yes) {
    //     //     isValid = false;
    //     //     document.querySelector('input[name="reason_known_allergies_yes"]').classList.add("input-error");
    //     // }
    //   }

    //   // Alert if any required field is missing
    //   if (!isValid) {
    //     alert("Please complete all required fields before proceeding.");
    //   }
    //   break;

    case 2:
      // Initialize isValid as true
      // let isValid = true;

      // Get the required elements
   
      const specific_medication_seeking = document.querySelector(
        'input[name="specific_medication_seeking"]:checked'
      );
      const known_allergies = document.querySelector(
        'input[name="known_allergies"]:checked'
      );
      // const reason_known_allergies_yes = document.querySelector(
      //   'input[name="reason_known_allergies_yes"]'
      // ).value;
      const reason_known_allergies_yes = document.getElementById( 'reasonknown-allergies-yes').value;
      const previously_taken_medi = document.querySelector(
        'input[name="previously_taken_medi"]:checked'
      );
      const known_nill_allergies = document.querySelector(
        'input[name="known_nill_allergies"]'
      ).value;
      const health_condition = document.querySelector(
        'input[name="health_condition"]'
      ).value;
      const over_the_counter_drugs = document.querySelector(
        'input[name="over_the_counter_drugs"]'
      ).value;
      const medication_used_previously = document.querySelector(
        'input[name="medication_used_previously"]:checked'
      );
      const plan_schedule = document.querySelector(
        'input[name="plan_schedule"]:checked'
      );
      // const health_condition = document.querySelector('input[name="health_condition"]:checked');
      const adverse_reactions = document.querySelector(
        'input[name="adverse_reactions"]:checked'
      );
      // const over_the_counter_drugs = document.querySelector('input[name="over_the_counter_drugs"]:checked');
      const healthcare_provider_person_recently = document.querySelector(
        'input[name="healthcare_provider_person_recently"]:checked'
      );

      // Validate required fields
    
      if (!previously_taken_medi) {
        isValid = false;
        document
          .querySelector('input[name="previously_taken_medi"]')
          .classList.add("input-error");
      }

      // Conditional validation based on previously_taken_medi-no
      if (document.getElementById("previouslytakenmedino").checked) {
        if (!specific_medication_seeking) {
          isValid = false;
          document
            .querySelector('input[name="specific_medication_seeking"]')
            .classList.add("input-error");
        }
        if (!known_nill_allergies) {
          isValid = false;
          document
            .querySelector('input[name="known_nill_allergies"]')
            .classList.add("input-error");
        }
        if (!medication_used_previously) {
          isValid = false;
          document
            .querySelector('input[name="medication_used_previously"]')
            .classList.add("input-error");
        }
        if (!plan_schedule) {
          isValid = false;
          document
            .querySelector('input[name="plan_schedule"]')
            .classList.add("input-error");
        }
      }

      // Conditional validation based on previously_taken_medi-yes
      if (document.getElementById("previouslytakenmediyes").checked) {
        if (!health_condition) {
          isValid = false;
          document
            .querySelector('input[name="health_condition"]')
            .classList.add("input-error");
        }

        if (!over_the_counter_drugs) {
          isValid = false;
          document
            .querySelector('input[name="over_the_counter_drugs"]')
            .classList.add("input-error");
        }
        // if (!adverse_reactions) {
        //   isValid = false;
        //   document
        //     .querySelector('input[name="adverse_reactions"]')
        //     .classList.add("input-error");
        // }

        if (!adverse_reactions) {
          isValid = false;
          document
            .querySelector('input[name="adverse_reactions"]')
            .classList.add("input-error");
        }

        if (!healthcare_provider_person_recently) {
          isValid = false;
          document
            .querySelector('input[name="healthcare_provider_person_recently"]')
            .classList.add("input-error");
        }

        if (!known_allergies) {
          isValid = false;
          document
            .querySelector('input[name="known_allergies"]')
            .classList.add("input-error");
        }

        

        if ( document.getElementById('known-allergies-yes').checked && !reason_known_allergies_yes) {
          isValid = false;
          document
            .querySelector('input[name="reason_known_allergies_yes"]')
            .classList.add("input-error");
        }
      
      }

      // Alert if any required field is missing
      if (!isValid) {
        alert("Please complete all required fields before proceeding.");
      }
      break;

    case 3:
      // Validate Step 3
      const title = document.getElementById("title").value;
      const fname = document.getElementById("fname").value.trim();
      const lname = document.getElementById("lname").value.trim();
      const gender = document.getElementById("gender").value;
      const dob = document.getElementById("dob").value;
      const email = document.getElementById("email").value.trim();
      const phone = document.getElementById("phone").value.trim();
      const address = document.getElementById("address").value.trim();
      const policyChecked =
        document.getElementById("certificate_policy").checked;

      if (
        !title ||
        !fname ||
        !lname ||
        !gender ||
        !dob ||
        !email ||
        !phone ||
        !address ||
        !policyChecked
      ) {
        isValid = false;
        alert("Please provide all required information.");
        // Add error class to fields (for demonstration; adjust as needed)
        if (!title)
          document.getElementById("title").classList.add("input-error");
        if (fname)
          document.getElementById("fname").classList.add("input-error");
        if (lname)
          document.getElementById("lname").classList.add("input-error");
        if (!gender)
          document.getElementById("gender").classList.add("input-error");
        if (!dob) document.getElementById("dob").classList.add("input-error");
        if (!email)
          document.getElementById("email").classList.add("input-error");
        if (!phone)
          document.getElementById("phone").classList.add("input-error");
        if (!address)
          document.getElementById("address").classList.add("input-error");
        if (!policyChecked)
          document
            .getElementById("certificate_policy")
            .classList.add("input-error");
      }
      break;

    case 4:
      // Validate Step 4
      const fields = ["appointment-day", "appointment-time"];
      fields.forEach((id) => {
        const field = document.getElementById(id);
        if (!field.value) {
          isValid = false;
          field.classList.add("input-error");
        }
      });

      if (!isValid) {
        alert("Please provide all required fields.");
      }
      break;

    case 5:
      // Validate Step 5
      // const payment = document.querySelector('input[name="payment"]:checked');
      // const stripeToken = document.querySelector('input[name="stripeToken"]');

      // if (!stripeToken) {
      //   isValid = false;
      //   alert("Please complete all required fields.");
      // }

      const country = document.getElementById("country");

      if (!country) {
        isValid = false;
        alert("Please complete all required fields.");
      }

      break;
  }

  if (isValid) {
    if (stepNumber < 5) {
      currentStep++;
      showStep(currentStep);
    }
  }
}

function prevStep() {
  if (currentStep > 1) {
    currentStep--;
    showStep(currentStep);
  }
}

// Initial call to show the first step
showStep(currentStep);

document.addEventListener("DOMContentLoaded", function () {
  const yesRadio = document.getElementById("known-allergies-yes");
  const noRadio = document.getElementById("known-allergies-no");
  const allergyDetails = document.getElementById("allergy-details");
  const reason_known_allergies_yes = document.getElementById(
    "reason_known_allergies_yes"
  );

  yesRadio.addEventListener("change", function () {
    if (this.checked) {
      allergyDetails.classList.remove("hidden");
      // reason_known_allergies_yes.required = true;
    }
  });

  noRadio.addEventListener("change", function () {
    if (this.checked) {
      allergyDetails.classList.add("hidden");
      // reason_known_allergies_yes.required = false;
    }
  });

  // Get references to the radio buttons and sections
  const previouslyTakenYes = document.getElementById("previouslytakenmediyes");
  const previouslyTakenNo = document.getElementById("previouslytakenmedino");
  const sectionYes = document.getElementById("previouslytakenmedicine-Yes");
  const sectionNo = document.getElementById("previouslytakenmedicine-No");

  // Function to set required attribute
  function setRequiredFields(section, required) {
    const inputs = section.querySelectorAll("input");
    inputs.forEach((input) => {
      if (required) {
        input.setAttribute("required", "required");
      } else {
        input.removeAttribute("required");
      }
    });
  }

  // // Event listeners for radio buttons
  // previouslyTakenYes.addEventListener("change", function () {
  //   if (this.checked) {
  //     sectionYes.style.display = "block";
  //     setRequiredFields(sectionYes, true);
  //     sectionNo.style.display = "none";
  //     setRequiredFields(sectionNo, false);
  //   }
  // });

  // previouslyTakenNo.addEventListener("change", function () {
  //   if (this.checked) {
  //     sectionNo.style.display = "block";
  //     setRequiredFields(sectionNo, true);
  //     sectionYes.style.display = "none";
  //     setRequiredFields(sectionYes, false);
  //   }
  // });

  // Initial setup
  if (previouslyTakenYes.checked) {
    sectionYes.style.display = "block";
    setRequiredFields(sectionYes, true);
    sectionNo.style.display = "none";
    setRequiredFields(sectionNo, false);
  } else if (previouslyTakenNo.checked) {
    sectionNo.style.display = "block";
    setRequiredFields(sectionNo, true);
    sectionYes.style.display = "none";
    setRequiredFields(sectionYes, false);
  }

  // Handling Treatment Selection
  const treatmentRadios = document.getElementsByName("treatment");
  const treatmentContainers = {
    "Mental Health": [
      "fluoxetine-container",
      "fluvoxaminemaleate-container",
      "sertraline-container",
      "citalopram-container",
      "cymbalta-container",
      "efexorxr-container",
      "lexapro-container",
      "lovan-container",
      "loxalate-container",
      "paroxetine-container",
      "pristiq-container",
      "zoloft-container",
      "mirtazapine-container",
    ],
    "Skin Conditions": [
      "advantan-container",
      "ats-container",
      "cmc-container",
      "diprosone-container",
      "doxycycline-container",
      "eleuphratointment-container",
      "elidelcream-container",
      "elocon-container",
      "epiduogel-container",
      "kenacombointment-container",
      "minomycin-container",
      "mupriocin-container",
      "novasonelotion-container",
      "prednisolone-container",
      "tretinoin-container",
      "daivobet-container",
    ],
    Asthama: [
      "Flixotide-container",
      "seretidesccuhaler-container",
      "seretidemdi-container",
      "symbicortrapihaler-container",
      "symbicortturbuhaler-container",
      "ventolin-container",
    ],
    "Women's Health": ["birthcontrol-container", "menopause-container"],
    "Blood Pressure, Cholesterol and Heart Conditions": [
      "amlodipine-container",
      "atenolol-container",
      "avapro-container",
      "avaprohct-container",
      "caduet-container",
      "coveram-container",
      "coversyl-container",
      "coversylplus-container",
      "crestor-container",
      "frusemide-container",
      "irbesartan-container",
      "lipitor-container",
      "metoprolol-container",
      "propranolol-container",
      "olmesartan-container",
      "perindopril-container",
      "olmetec-container",
      "reaptan-container",
      "telmisartan-container",
    ],
    Diabetes: [
      "metformin500-container",
      "metformin1000-container",
      "metforminxr500-container",
      "novorapidflexpen-container",
      "novorapidpenfill-container",
      "novorapidvial-container",
      "diaforminxr1000-container",
    ],
    "Men's Health": [
      "priligy-container",
      "sildenafil50-container",
      "sildenafil100-container",
      "tadalafil10-container",
      "tadalafil20-container",
      "tadalafil50-container",
    ],
    "Dyspepsia/Heartburn and Reflux": [
      "zoton15-container",
      "zoton30-container",
      "nexium20-container",
      "nexium40-container",
      "acimax10-container",
      "acimax20-container",
      "pariet10-container",
      "pariet20-container",
      "somac20-container",
      "somac40-container",
    ],
    "Pain and Inflammation": [
      "meloxicam7_5-container",
      "meloxicam15-container",
      "celebrex60-container",
      "celebrex30-container",
      "naprosyn250-container",
      "naprosyn500-container",
      "voltaren-container",
      "voltarenrapid-container",
      "somac40-container",
    ],
    "Allergies and Hay Fever": [
      "avamys-container",
      "dymista-container",
      "omnaris-container",
      "rhinocort-container",
    ],
    Gout: [
      "allopurinol100-container",
      "allopurinol300-container",
      "colgout500-container",
    ],
    "Migraine Relief": [
      "imigran50-container",
      "imigran20-container",
      "maxaltwafers10-container",
      "relpax40-container",
      "relpax80-container",
    ],
    "Morning Sickness": ["zofran10-container", "zofran4-container"],
  };

  function updateTreatmentContainers() {
    const selectedTreatment = document.querySelector(
      'input[name="treatment"]:checked'
    );
    const value = selectedTreatment ? selectedTreatment.value : null;

    Object.keys(treatmentContainers).forEach((treatmentType) => {
      treatmentContainers[treatmentType].forEach((containerId) => {
        const container = document.getElementById(containerId);
        if (value === treatmentType) {
          container.classList.remove("hidden");
        } else {
          container.classList.add("hidden");
        }
      });
    });
  }

  treatmentRadios.forEach((radio) => {
    radio.addEventListener("change", updateTreatmentContainers);
  });

  updateTreatmentContainers();

  // Handling Prescription Selection
  const prescriptionRadios = document.getElementsByName("prescriptionafter");
  const prescriptionContainers = {
    "Fluvoxamine Maleate": "fluvoxamine-dosage",
    "Sertraline (Zoloft)": "sertralinezoloft-dosage",
    Citalopram: "citalopram-dosage",
    Cymbalta: "cymbalta-dosage",
    "Efexor-XR (Venlafaxine)": "efexorxr-dosage",
    "Lexapro (Escitalopram)": "lexapro-dosage",
    "Loxalate (Escitalopram)": "loxalate-dosage",
    "Pristiq (Desvenlafaxine)": "pristiq-dosage",
    Zoloft: "zoloft-dosage",
    Mirtazapine: "mirtazapine-dosage",
    Advantan: "advantan-dosage",
    Diprosone: "diprosone-dosage",
    Doxycycline: "doxycycline-dosage",
    Elocon: "elocon-dosage",
    Daivobet: "daivobet-dosage",
    Flixotide: "flixotide-dosage",
    "Seretide Accuhaler": "SeretideAccuhaler-dosage",
    "Seretide MDI": "SeretideMDI-dosage",
    "Symbicort Rapihaler": "SymbicortRapihaler-dosage",
    "Symbicort Turbuhaler": "SymbicortTurbuhaler-dosage",
    Ventolin: "Ventolin-dosage",
    "Birth Control": "BirthControl-dosage",
    Menopause: "Menopause-dosage",
    // New Fields
    Amlodipine: "Amlodipine-dosage",
    "Avapro HCT": "AvaproHCT-dosage",
    Caduet: "Caduet-dosage",
    Coveram: "Coveram-dosage",
    Coversyl: "Coversyl-dosage",
    "Coversyl Plus": "CoversylPlus-dosage",
    Crestor: "Crestor-dosage",
    Frusemide: "Frusemide-dosage",
    Irbesartan: "Irbesartan-dosage",
    Lipitor: "Lipitor-dosage",
    Metoprolol: "Metoprolol-dosage",
    Propranolol: "Propranolol-dosage",
    Olmesartan: "Olmesartan-dosage",
    Perindopril: "Perindopril-dosage",
    Reaptan: "Reaptan-dosage",
    "Telmisartan (Micardis)": "TelmisartanMicardis-dosage",
  };

  function updatePrescriptionContainers() {
    const selectedPrescription = document.querySelector(
      'input[name="prescriptionafter"]:checked'
    );
    const selectedValue = selectedPrescription
      ? selectedPrescription.value
      : null;

    Object.keys(prescriptionContainers).forEach((prescription) => {
      const containerId = prescriptionContainers[prescription];
      const container = document.getElementById(containerId);
      if (selectedValue === prescription) {
        container.classList.remove("hidden");
      } else {
        container.classList.add("hidden");
      }
    });

    // Handle the requirement for prescription dosage fields
    Object.keys(prescriptionContainers).forEach((prescription) => {
      const containerId = prescriptionContainers[prescription];
      const container = document.getElementById(containerId);
      const isRequired = selectedValue === prescription;
      container.querySelectorAll('input[type="radio"]').forEach((field) => {
        field.required = isRequired;
      });
    });
  }

  prescriptionRadios.forEach((radio) => {
    radio.addEventListener("change", updatePrescriptionContainers);
  });

  updatePrescriptionContainers();

  // Handling Previously Taken Medicine Selection
  const previouslyTakenRadios = document.getElementsByName(
    "previously_taken_medi"
  );
  const previouslyTakenContainers = {
    No: "previouslytakenmedicine-No",
    Yes: "previouslytakenmedicine-Yes",
  };

  function updatePreviouslyTakenContainers() {
    const selectedValue = document.querySelector(
      'input[name="previously_taken_medi"]:checked'
    )?.value;

    Object.keys(previouslyTakenContainers).forEach((option) => {
      const containerId = previouslyTakenContainers[option];
      const container = document.getElementById(containerId);
      if (selectedValue === option) {
        container.classList.remove("hidden");
      } else {
        container.classList.add("hidden");
      }
    });
  }

  previouslyTakenRadios.forEach((radio) => {
    radio.addEventListener("change", updatePreviouslyTakenContainers);
  });

  updatePreviouslyTakenContainers();

  // Function to update dosage fields

  // function updateDosageFields() {
  //   // Hide all dosage sections
  //   Object.value(medicationDosageMap).forEach((dosageId) => {
  //     const dosageDiv = document.getElementById(dosageId);
  //     if (dosageDiv) dosageDiv.classList.add("hidden");
  //   });

  //   // Get selected medication
  //   const selectedMedication = document.querySelector(
  //     'input[name="prescriptionafter"]:checked'
  //   );
  //   if (selectedMedication) {
  //     const dosageId = medicationDosageMap[selectedMedication.value];
  //     if (dosageId) {
  //       const dosageDiv = document.getElementById(dosageId);
  //       if (dosageDiv) {
  //         dosageDiv.classList.remove("hidden");
  //         dosageDiv
  //           .querySelectorAll('input[type="radio"]')
  //           .forEach((radio) => (radio.required = true));
  //       }
  //     }
  //   }
  // }

  // Attach event listeners
  // document
  //   .querySelectorAll('input[name="prescriptionafter"]')
  //   .forEach((radio) => {
  //     radio.addEventListener("change", updateDosageFields);
  //   });

  // // Initialize on page load
  // updateDosageFields();
});
