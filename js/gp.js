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

function check_val(elm)
{
  if(elm.nextSibling.nextSibling.nextSibling.nextSibling.value.length == 0)
  {
    var btn = document.querySelector("#entrer_btn");
    btn.type = "hidden";
    alert("Donner un magasin destination");
    return false;
  }
  else
  {
    return true;
  }
}
function hide_entrer(inp)
{
  var btn = document.querySelector("#entrer_btn");
  if(inp.value != "")
  {
    btn.type = "hidden";
  }
  else
  {
    btn.type = "submit";
  }
}
function hide_trans()
{
  var btn = document.querySelector("#transfert_btn");
  btn.type = "hidden";
  return true;
}

/*function add_input_mag(elm)
{
  var inp = document.createElement("input");
  inp.id = "inp_mag_dest";
  inp.type = "text";
  inp.name = "mag_dest";
  inp.placeholder = "Magasin destination";
  elm.parentNode.insertBefore(inp,elm.nextSibling.nextSibling.nextSibling);
}
function del_input_mag()
{
  var inp = document.querySelector("#inp_mag_dest");
  if(inp)
  {
    inp.parentNode.removeChild(inp);
  }
}*/
