document.addEventListener('DOMContentLoaded', (e)=> {
  checkSpace(e);
})

function checkSpace(e) {
  const inputRole = document.querySelectorAll('.input-role');
  if (inputRole.length > 0) {
    inputRole.forEach(input => {
      input.addEventListener('input', (e) => {
        e.target.value = e.target.value.replace(/^ /, '');
      });
    })
  }
}
