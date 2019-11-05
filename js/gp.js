var sel1 = document.querySelector('#F');
var sel2 = document.querySelector('#SF');
var options2 = sel2.querySelectorAll('option');
var b = 1;

function giveSelection(selValue)
{
  sel2.innerHTML = '';
  for(var i = 0; i < options2.length; i++) 
  {
    if(options2[i].dataset.option === selValue) 
    {
      sel2.appendChild(options2[i]);
   }
  }
}

giveSelection(sel1.value);


function pass_check(id1,id2,ref)
{
	var pass1 = document.querySelector(id1);
	var pass2 = document.querySelector(id2);

	if(pass1.value != pass2.value)
	{
		window.location.href = 'index.php?' + ref + '&error=les deux mot de passes ne correspond pas';
		return false;
	}
}

function conf()
{
  if(b == 0) return false;
  else
  {
    var form = document.querySelector('form');
    var label = document.createElement('label');
    label.innerHTML = "confirmation";
    label.appendChild(document.createElement('input'));
    form.insertBefore(label,form.childNodes[4]);
    b = 0;
  }
}