/* eslint-disable no-shadow */
/* eslint-disable no-empty */
/* eslint-disable no-param-reassign */

function init() {
  const loginBtn = document.querySelector('.login-btn');
  function clearErrMsg() {
    const span = document.querySelector('span.warning');
    if (span) {
      setInterval(() => {
        span.remove();
      }, 3000);
    }
  }

  async function fetchAvatarIMG() {
    const rawResponse = await fetch('https://dog.ceo/api/breeds/image/random');
    const json = await rawResponse.json();
    const url = json.message;
    return url;
  }

  function assignAvatarIMG() {
    const imgs = document.querySelectorAll('.avatar-img');
    if (imgs.length) {
      imgs.forEach(async (img) => {
        const dogIMG = await fetchAvatarIMG();
        img.src = dogIMG;
      });
    }
  }

  function checkSpace() {
    const nickname = document.querySelector('.nick');
    const user = document.querySelector('.user');
    const password = document.querySelector('.password');
    if (nickname) {
      nickname.addEventListener('input', (e) => {
        e.target.value = e.target.value.replace(/^ /, '');
      });
    }
    function check(e) {
      e.target.value = e.target.value.replace(/ /g, '');
    }
    if (user && password) {
      user.addEventListener('input', check);
      password.addEventListener('input', check);
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
      loginDIV.insertBefore(span, inputWrapper);
      const s = document.querySelector('span.warning');
      setInterval(() => {
        s.remove();
      }, 3000);
    }
  }

  let clicked = false;


  loginBtn.addEventListener('click', (e) => {
    if (clicked === false) {
      clicked = true;
    } else if (clicked === true) {
      e.preventDefault();
      setTimeout(() => {
        clicked = false;
      }, 3000);
    }
    const atMsgPage = document.querySelector('.board__type-comment-title');
    const war = document.querySelectorAll('span.warning');
    war.forEach((w) => {
      w.remove();
    });
    if (!atMsgPage) {
      warning(e);
    } else {
      const typeArea = document.querySelector('.type-comment').value;
      const typeAreaBox = document.querySelector('.type-comment');
      if (!typeArea) {
        e.preventDefault();
        const commentForm = document.querySelector('.board__type-comment');
        const span = document.createElement('span');
        span.classList.add('warning');
        span.innerText = '內容不得為空!';
        commentForm.insertBefore(span, typeAreaBox);
      }
      const s = document.querySelector('span.warning');
      if (s) {
        setInterval(() => {
          s.remove();
        }, 3000);
      }
    }
  });

  const pages = document.querySelector('.pages');


  function getCurrentPage() {
    const currentIdx = Number(window.location.search.match(/\d+/)[0]) - 1;
    const currentPage = document.querySelectorAll('.page')[currentIdx];
    return currentPage;
  }

  function changePage(e) {
    const dataPage = e.target.dataset.page;
    const location = `http://mentor-program.co/mtr04group2/John/week9/hw1-board/index.php?page=${dataPage}`;
    window.location.href = location;
  }
  function toPrevPage(targetPage) {
    const dataPage = targetPage.dataset.page;
    const location = `http://mentor-program.co/mtr04group2/John/week9/hw1-board/index.php?page=${dataPage}`;
    window.location.href = location;
  }
  function toNextPage(targetPage) {
    const dataPage = targetPage.dataset.page;
    const location = `http://mentor-program.co/mtr04group2/John/week9/hw1-board/index.php?page=${dataPage}`;
    window.location.href = location;
  }

  getCurrentPage().classList.add('current');
  const allPages = document.querySelectorAll('.page');
  allPages[0].classList.add('show');
  allPages[allPages.length - 1].classList.add('show');

  function createDot() {
    const div = document.createElement('div');
    div.className = 'dot';
    div.textContent = '...';

    return div;
  }

  const currentPage = getCurrentPage();
  const pageIdx = currentPage.dataset.page - 1;
  if (currentPage.dataset.page - 1 === 0) {
    const currentIndex = currentPage.dataset.page - 1;
    const parent = document.querySelector('.pages');
    allPages[currentIndex + 1].classList.add('show');
    allPages[currentIndex + 2].classList.add('show');
    const dotDiv = createDot();
    parent.insertBefore(dotDiv, allPages[allPages.length - 1]);
  } else if (currentPage.dataset.page - 1 === allPages.length - 1) {
    const currentIndex = currentPage.dataset.page - 1;
    const parent = document.querySelector('.pages');
    allPages[currentIndex - 1].classList.add('show');
    allPages[currentIndex - 2].classList.add('show');
    const dotDiv = createDot();
    parent.insertBefore(dotDiv, allPages[1]);
  } else if (pageIdx + 1 !== allPages.length - 1 && pageIdx - 1 !== 0) {
    const currentIndex = currentPage.dataset.page - 1;
    const parent = document.querySelector('.pages');
    allPages[currentIndex - 1].classList.add('show');
    allPages[currentIndex + 1].classList.add('show');
    const dotDivStart = createDot();
    const dotDivEnd = createDot();
    if (pageIdx - 2 !== 0) {
      parent.insertBefore(dotDivStart, allPages[1]);
    }
    if (pageIdx + 2 !== allPages.length - 1) {
      parent.insertBefore(dotDivEnd, allPages[allPages.length - 1]);
    }
  } else if (pageIdx + 1 === allPages.length - 1) {
    const currentIndex = currentPage.dataset.page - 1;
    const parent = document.querySelector('.pages');
    allPages[currentIndex - 1].classList.add('show');
    allPages[currentIndex - 2].classList.add('show');
    const dotDiv = createDot();
    parent.insertBefore(dotDiv, allPages[1]);
  } else if (pageIdx - 1 === 0) {
    const currentIndex = currentPage.dataset.page - 1;
    const parent = document.querySelector('.pages');
    allPages[currentIndex + 1].classList.add('show');
    allPages[currentIndex + 2].classList.add('show');
    const dotDiv = createDot();
    parent.insertBefore(dotDiv, allPages[allPages.length - 1]);
  }


  pages.addEventListener('click', (e) => {
    if (e.target.classList.contains('page')) {
      changePage(e);
    } else if (e.target.className === 'prev') {
      const allPages = document.querySelectorAll('.page');
      allPages.forEach((page, i) => {
        if (page.classList.contains('current') && allPages[i - 1] !== undefined) {
          toPrevPage(allPages[i - 1]);
        }
      });
    } else if (e.target.className === 'next') {
      const allPages = document.querySelectorAll('.page');
      allPages.forEach((page, i) => {
        if (page.classList.contains('current') && allPages[i + 1] !== undefined) {
          toNextPage(allPages[i + 1]);
        }
      });
    }
  });

  assignAvatarIMG();
  clearErrMsg();
  checkSpace();
}

document.addEventListener('DOMContentLoaded', init);
