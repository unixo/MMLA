function enlargeImage()
{
   document.getElementById('prdimage').height="260";
   document.getElementById('prdimage').width="260";
   document.getElementById('glassmsg').innerHTML = "Single-click to reduce";
}

function dropImage()
{
   document.getElementById('prdimage').height="130";
   document.getElementById('prdimage').width="130";
   document.getElementById('glassmsg').innerHTML = "Double-click to enlarge";
}