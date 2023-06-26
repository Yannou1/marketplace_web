function showDiv() {
    var div2 = document.querySelector('.div2');
    div2.classList.add('show');
  }
  
  function hideDiv() {
    var div2 = document.querySelector('.div2');
    var isHovered = div2.matches(':hover');
    
    if (!isHovered) {
      div2.classList.remove('show');
    }
  }
  