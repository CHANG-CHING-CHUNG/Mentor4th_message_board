               
function init() {
  const loginBtn = document.querySelector('.login-btn');
  function clearErrMsg() {
    const span = document.querySelector('span.warning');
    if(span) {
      setInterval(() => {
        span.remove();
      }, 3000);
    }
    
  }

  async function fetchAvatarIMG() {
    const rawResponse = await fetch("https://dog.ceo/api/breeds/image/random");
    const json = await rawResponse.json();
    const url =  json.message;
    return url;
  }

 function assignAvatarIMG() {
    const imgs = document.querySelectorAll(".avatar-img");
    if (imgs.length) {
      imgs.forEach(async (img)=> {
        const dogIMG = await fetchAvatarIMG();
        img.src = dogIMG;
      })
    }
  }

  function checkSpace() {
    const nickname = document.querySelector('.nick');
    const user = document.querySelector('.user');
    const password = document.querySelector('.password');
    if (nickname) {
      nickname.addEventListener('input',(e) => {
        e.target.value = e.target.value.replace(/^ /,"");
      });
    }
    if (user && password) {
      user.addEventListener('input',check);
      password.addEventListener('input', check);
    }
    function check(e) {
        e.target.value = e.target.value.replace(/ /g, "");
    }
  
  }
  
  function warning(e) {
    const user = document.querySelector('.user').value;
    const password = document.querySelector('.password').value;
    const loginDIV = document.querySelector('.board__login-div');
    const inputWrapper = document.querySelectorAll('.input-wrapper')[0];
    if (user && password) {
      
    } else {
      e.preventDefault();
      const span = document.createElement('span');
      
      span.classList.add('warning');
      span.innerText = '帳號及密碼不得為空!';
      loginDIV.insertBefore(span,inputWrapper);
      const s = document.querySelector('span.warning');
      setInterval(()=> {
        s.remove();
      },3000);
    }
  }

  let clicked = false;


  loginBtn.addEventListener('click', (e)=> {
    if (clicked === false) {
      clicked = true;
    } else if (clicked === true) {
      e.preventDefault();
      setTimeout(()=> {
        clicked = false;
      },3000);
    }
    const atMsgPage = document.querySelector(".board__type-comment-title");
    const war = document.querySelectorAll('span.warning');
    war.forEach((w) => {
      w.remove();
    })
    if (!atMsgPage) {
      warning(e)
    } else {
      const typeArea = document.querySelector(".type-comment").value;
      const typeAreaBox = document.querySelector(".type-comment");
      if(!typeArea) {
        e.preventDefault();
        const commentForm = document.querySelector('.board__type-comment');
        const span = document.createElement('span');
        span.classList.add('warning');
        span.innerText = '內容不得為空!';
        commentForm.insertBefore(span,typeAreaBox);
      }
      const s = document.querySelector('span.warning');
      if(s) {
        setInterval(()=> {
          s.remove();
        },3000); 
      }
    }
  });

  assignAvatarIMG()
  clearErrMsg();
  checkSpace();
}

document.addEventListener("DOMContentLoaded", init);