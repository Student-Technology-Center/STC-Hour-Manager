//Validation Javascript document 
  
function validateForm(frm) 
{ 
    var x; 
    var currentfrmelement; 

    var tm1;
    var tm2;

    tm1 = frm.
  
    for(var i=0; i < frm.elements.length; i++) 
    { 
       // if(frm.elements[i].type == "select") 
          //  continue; 
  
        x = frm.elements[i]; 
        currentfrmelement = x.id; 
        if (x.value == "" || x.value == null)  
        { 
            txt = "You need to fill out " + currentfrmelement + " field"; 
            alert(txt); 
            x.focus(); 
            return false; 
        }
    } 
} 
