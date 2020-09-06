document.addEventListener("DOMContentLoaded", (e) => {

  
  const pages = document.querySelector('.pages');
  
  
  function getCurrentPage() {
    let currentIdx;
    if (window.location.search.match(/\d+/) != null) {
      currentIdx = Number(window.location.search.match(/\d+/)[0]) - 1;
    } else {
      currentIdx = 1;
    }
    const currentPage = document.querySelectorAll('.page')[currentIdx];
    return currentPage;
  }
  
  function changePage(e) {
    const dataPage = e.target.dataset.page;
    const location = `http://mentor-program.co/mtr04group2/John/week11/hw1-board/admin.php?page=${dataPage}`;
    // const location = `http://localhost/board11/admin.php?page=${dataPage}`;
    window.location.href = location;
  }
  function toPrevPage(targetPage) {
    const dataPage = targetPage.dataset.page;
    const location = `http://mentor-program.co/mtr04group2/John/week11/hw1-board/admin.php?page=${dataPage}`;
    // const location = `http://localhost/board11/admin.php?page=${dataPage}`;
    window.location.href = location;
  }
  function toNextPage(targetPage) {
    const dataPage = targetPage.dataset.page;
    const location = `http://mentor-program.co/mtr04group2/John/week11/hw1-board/admin.php?page=${dataPage}`;
    // const location = `http://localhost/board11/admin.php?page=${dataPage}`;
    window.location.href = location;
  }
  
  function initPagination() {
    if(getCurrentPage() != undefined) {
  
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
        if (allPages[currentIndex + 1] != null) {
          allPages[currentIndex + 1].classList.add('show');
        }
        if (allPages[currentIndex + 2] != null) {
          allPages[currentIndex + 2].classList.add('show');
          if (allPages.length >= 5) {
            const dotDiv = createDot();
            parent.insertBefore(dotDiv, allPages[allPages.length - 1]);
          }
        }
      } else if (currentPage.dataset.page - 1 === allPages.length - 1) {
        
        const currentIndex = currentPage.dataset.page - 1;
        const parent = document.querySelector('.pages');
        if (allPages[currentIndex - 1] != null) {
          allPages[currentIndex - 1].classList.add('show');
        }
        if(allPages[currentIndex - 2] != null) {
          allPages[currentIndex - 2].classList.add('show');
          if (allPages.length >= 5) {
            const dotDiv = createDot();
            parent.insertBefore(dotDiv, allPages[1]);
          }
    
        }
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
        if (allPages[currentIndex - 3] != null) {
          allPages[currentIndex - 3].classList.add('show');
          if (allPages.length >= 5) {
            const dotDiv = createDot();
            parent.insertBefore(dotDiv, allPages[1]);
          }
        }
      } else if (pageIdx - 1 === 0) {
        const currentIndex = currentPage.dataset.page - 1;
        const parent = document.querySelector('.pages');
        allPages[currentIndex + 1].classList.add('show');
        if (allPages[currentIndex + 3] != null) {
          allPages[currentIndex + 3].classList.add('show');
          if (allPages.length >= 5) {
            const dotDiv = createDot();
            parent.insertBefore(dotDiv, allPages[allPages.length - 1]);
          }
        }
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
    }
  }

  initPagination()
})
