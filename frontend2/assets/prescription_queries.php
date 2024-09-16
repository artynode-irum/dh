<!-- <script>
  function calculateAge() {
    var dob = document.getElementById('dob').value;
    if (!dob) {
      document.getElementById('age').textContent = '';
      return;
    }

    var dobDate = new Date(dob);
    var today = new Date();
    var age = today.getFullYear() - dobDate.getFullYear();
    var monthDifference = today.getMonth() - dobDate.getMonth();

    if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < dobDate.getDate())) {
      age--;
    }

    document.getElementById('age').textContent = age + ' years';
  }
</script> -->





<script>

  document.addEventListener("DOMContentLoaded", function () {
    const treatmentRadios = document.getElementsByName("treatment");
    const containers = {
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
        "mirtazapine-container"
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
        "daivobet-container"
      ],
      "Asthama": [
        "Flixotide-container",
        "seretidesccuhaler-container",
        "seretidemdi-container",
        "symbicortrapihaler-container",
        "symbicortturbuhaler-container",
        "ventolin-container"
      ],
      "Women's Health": [
        "birthcontrol-container",
        "menopause-container"
      ],
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
        "telmisartan-container"
      ],
      "Diabetes": [
        "metformin500-container",
        "metformin1000-container",
        "metforminxr500-container",
        "novorapidflexpen-container",
        "novorapidpenfill-container",
        "novorapidvial-container",
        "diaforminxr1000-container"
      ],
      "Men's Health": [
        "priligy-container",
        "sildenafil50-container",
        "sildenafil100-container",
        "tadalafil10-container",
        "tadalafil20-container",
        "tadalafil50-container"
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
        "somac40-container"
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
        "somac40-container"
      ],
      "Allergies and Hay Fever": [
        "avamys-container",
        "dymista-container",
        "omnaris-container",
        "rhinocort-container"
      ],
      "Gout": [
        "allopurinol100-container",
        "allopurinol300-container",
        "colgout500-container"
      ],
      "Migraine Relief": [
        "imigran50-container",
        "imigran20-container",
        "maxaltwafers10-container",
        "relpax40-container",
        "relpax80-container"
      ],
      "Morning Sickness": [
        "zofran10-container",
        "zofran4-container"
      ]
    };

    function updatePrescriptions() {
      const selectedTreatment = document.querySelector('input[name="treatment"]:checked');
      const value = selectedTreatment ? selectedTreatment.value : null;

      Object.keys(containers).forEach(treatmentType => {
        containers[treatmentType].forEach(containerId => {
          const container = document.getElementById(containerId);
          if (value === treatmentType) {
            container.classList.remove("hidden");
          } else {
            container.classList.add("hidden");
          }
        });
      });
    }

    treatmentRadios.forEach(radio => {
      radio.addEventListener("change", updatePrescriptions);
    });

    // Initial check in case of pre-selection
    updatePrescriptions();
  });

</script>


<script>
  document.addEventListener("DOMContentLoaded", function () {
    const prescriptionRadios = document.getElementsByName("prescriptionafter");
    const containers = {
      "Fluvoxamine Maleate": "fluvoxamine-dosage",
      "Sertraline (Zoloft)": "sertralinezoloft-dosage",
      "Citalopram": "citalopram-dosage",
      "Cymbalta": "cymbalta-dosage",
      "Efexor-XR (Venlafaxine)": "efexorxr-dosage",
      "Lexapro (Escitalopram)": "lexapro-dosage",
      "Loxalate (Escitalopram)": "loxalate-dosage",
      "Pristiq (Desvenlafaxine)": "pristiq-dosage",
      "Zoloft": "zoloft-dosage",
      "Mirtazapine": "mirtazapine-dosage",
      "Advantan": "advantan-dosage",
      "Diprosone": "diprosone-dosage",
      "Doxycycline": "doxycycline-dosage",
      "Elocon": "elocon-dosage",
      "Daivobet": "daivobet-dosage",
      "Flixotide": "flixotide-dosage",
      "Seretide Accuhaler": "SeretideAccuhaler-dosage",
      "Seretide MDI": "SeretideMDI-dosage",
      "Symbicort Rapihaler": "SymbicortRapihaler-dosage",
      "Symbicort Turbuhaler": "SymbicortTurbuhaler-dosage",
      "Ventolin": "Ventolin-dosage",
      "Birth Control": "BirthControl-dosage",
      "Menopause": "Menopause-dosage",
      
      
      // **************** New Feilds Added Start ******** 
      "Amlodipine": "Amlodipine-dosage",
      "Avapro HCT": "AvaproHCT-dosage",
      "Caduet": "Caduet-dosage",
      "Coveram": "Coveram-dosage",
      "Coversyl": "Coversyl-dosage",
      "Coversyl Plus": "CoversylPlus-dosage",
      "Crestor": "Crestor-dosage",
      "Frusemide": "Frusemide-dosage",
      "Irbesartan": "Irbesartan-dosage",
      "Lipitor": "Lipitor-dosage",
      "Metoprolol": "Metoprolol-dosage",
      "Propranolol": "Propranolol-dosage",
      "Olmesartan": "Olmesartan-dosage",
      "Perindopril": "Perindopril-dosage",
      "Reaptan": "Reaptan-dosage",
      "Telmisartan (Micardis)": "TelmisartanMicardis-dosage"
    };

    function updatePrescriptions() {
      const selectedPrescription = document.querySelector('input[name="prescriptionafter"]:checked');
      const selectedValue = selectedPrescription ? selectedPrescription.value : null;

      Object.keys(containers).forEach(prescription => {
        const containerId = containers[prescription];
        const container = document.getElementById(containerId);
        if (selectedValue === prescription) {
          container.classList.remove("hidden");
        } else {
          container.classList.add("hidden");
        }
      });
    }

    prescriptionRadios.forEach(radio => {
      radio.addEventListener("change", updatePrescriptions);
    });

    // Initial check in case of pre-selection
    updatePrescriptions();
  });

</script>








<script>
  document.addEventListener("DOMContentLoaded", function () {
    const prescriptionRadios = document.getElementsByName("previously_taken_medi");
    const containers = {
      "No": "previouslytakenmedicine-No",
      "Yes": "previouslytakenmedicine-Yes",
      // "Sertraline (Zoloft)": "sertralinezoloft-dosage",
      // "Citalopram": "citalopram-dosage",
      // "Ventolin": "Ventolin-dosage"
    };

    function updatePrescriptions() {
      const selectedPrescription = document.querySelector('input[name="previously_taken_medi"]:checked');
      const selectedValue = selectedPrescription ? selectedPrescription.value : null;

      Object.keys(containers).forEach(prescription => {
        const containerId = containers[prescription];
        const container = document.getElementById(containerId);
        if (selectedValue === prescription) {
          container.classList.remove("hidden");
        } else {
          container.classList.add("hidden");
        }
      });
    }

    prescriptionRadios.forEach(radio => {
      radio.addEventListener("change", updatePrescriptions);
    });

    // Initial check in case of pre-selection
    updatePrescriptions();
  });


</script>