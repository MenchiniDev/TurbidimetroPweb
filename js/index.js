document.getElementById("turbidimeterForm").addEventListener("submit", validateCoordinates);

function validateCoordinates() {
  const latitudineInput = document.getElementById('latitudine');
  const longitudineInput = document.getElementById('longitudine');
  const latitudineValue = latitudineInput.value.trim(); //rimuovo gli spazi all'inizio e alla fine
  const longitudineValue = longitudineInput.value.trim(); //uguale

  const latitudinePattern = /^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)$/;
  const longitudinePattern = /^[-+]?((1[0-7]\d|0?\d{1,2})(\.\d+)?|180(\.0+)?)$/;

  if (!latitudinePattern.test(latitudineValue)) {
      alert('Inserisci una latitudine valida nel formato decimale (es. 40.7128 o -37.8136)');
      latitudineInput.focus(); //attira l'attenzione verso l'input errato
      return false;
  }

  if (!longitudinePattern.test(longitudineValue)) {
      alert('Inserisci una longitudine valida nel formato decimale (es. -74.0060 o 144.9631)');
      longitudineInput.focus();
      return false;
  }

  return true;
}


const addT = document.getElementById("turbidimeterForm");

addT.addEventListener("submit",(e)=>
{
  e.preventDefault(); // Evita il comportamento predefinito dell'invio del form

  const formData = new FormData();
  //ho dovuto costruire manualmente la form per la conversione ad intero di id
  formData.append('identificatore', parseInt(document.getElementById("identificatore").value));
  formData.append('latitudine', document.getElementById("latitudine").value);
  formData.append('longitudine', document.getElementById("longitudine").value); 

  fetch("php/ajax/newTurbidimeter.php", {
    method: 'POST',
    body: formData
  })
.then(response => response.json())
.then(data => {
    if(!data['result']){
      console.log("errore");
    }
    else{
        console.log("tutto ok");
    }
});
});