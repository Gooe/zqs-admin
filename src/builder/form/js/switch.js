form.on('switch(switch_{$name})', function(data){
  if (data.elem.checked) {
  	$(data.othis).next().val(1);
  }else{
  	$(data.othis).next().val(0);
  }
}); 