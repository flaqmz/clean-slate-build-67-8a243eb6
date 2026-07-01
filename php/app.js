(function(){
  var el = document.getElementById('countdown');
  if(!el) return;
  var s = parseInt(el.dataset.start,10) || 0;
  function render(){
    var mm = String(Math.floor(s/60)).padStart(2,'0');
    var ss = String(s%60).padStart(2,'0');
    el.textContent = mm+':'+ss;
  }
  render();
  var id = setInterval(function(){
    if(s<=0){clearInterval(id);return;}
    s--; render();
  },1000);
})();
