const detailForm = document.getElementById("detailForm");
const packBtn = document.getElementById("buttonAddPack");

let checkboxDataCount = 0;

function updateButtonPack() {
  if (checkboxDataCount >= 3) {
    packBtn.disabled = false;
  } else {
    packBtn.disabled = true;
  }
}

packBtn.addEventListener("click", async function () {
  try {
    const response = await fetch('get_variant_data.php?id=<?= $idpoproduk ?>');
    const data = await response.json();

    data.forEach(variant => {
      const checkbox = document.createElement("input");
      checkbox.type = "checkbox";
      checkbox.name = "checkboxItem[]";
      checkbox.value = variant;
      checkbox.className = "form-control";

      const label = document.createElement("label");
      label.appendChild(checkbox);
      label.appendChild(document.createTextNode(variant));
      detailForm.appendChild(label);
    });

    checkboxDataCount += data.length;
    updateButtonPack();
  } catch (error) {
    console.error('Error:', error);
  }
});
