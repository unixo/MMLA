// svglib.js
// Copyright(c), 2007, Jeff Schiller, CodeDread.  All rights reserved. 
// Permission to use, modify, distribute, and publicly display this file is hereby
// granted. This file is provided "AS-IS" with absolutely no warranties of any kind. 
//

// checks for ActiveX Adobe SVG control
function isASVInstalled()
{
    try{
        var asv = new ActiveXObject("Adobe.SVGCtl");
        return true;
    }
    catch(e){
    }
    return false;
}

var bHasASVActiveX = isASVInstalled();

// This is the JS function (for IE) that works in conjunction with the PHP library svginlay.inc
function inlaySVG(id, svgFilename, width, height, fallbackContentAsXmlString) {
    if(bHasASVActiveX) {
        document.write('<embed id="' + id + '" width="' + width + '" height="' + height + 
            '" src="' + svgFilename + '" class="svgex" wmode="transparent" />');
    }
    // TO DO: consider Batik SVG viewer applet here?
    else {
        document.write(fallbackContentAsXmlString);
    }
}

// This function scours the document for any SVG <object> tags and 
// in the case of IE+ASV changes them into equivalent <embed> tags
// NOTE:  This isn't used anymore, I use IE conditional comments to get around this.
function cleanSvg()
{
    var objs = document.getElementsByTagName("object");
    if(objs && objs.length) {
        var objsToChange = new Array();
        var newObjText = new Array();
        
        // IE has mimeTypes empty, thus length = 0
        var bDoEmbed = ((navigator.mimeTypes == null || 
                         navigator.mimeTypes.length == 0) &&
                         isASVInstalled());

        // loop through all <object> tags
        for(var loop = 0; loop < objs.length; ++loop) {
            var obj = objs[loop];
            // if the object type is SVG and we are using IE, transmute it
            // into an equivalent <embed> tag.  IE has a mimeTypes of zero
            // length, other browsers do not.
            if(obj && obj.type == "image/svg+xml" && bDoEmbed)
            {
                // change it into an <embed>
                var outerstr = "<embed class='svgex' src='" + obj.data + "' ";

                if(obj.width) {
                    outerstr += "width='" + obj.width + "' ";
                }
                if(obj.height) {
                    outerstr += "height='" + obj.height + "' ";
                }
                if(obj.id) {
                    outerstr += "id='" + obj.id +"' ";
                }
                outerstr += "><noembed>" + obj.innerHTML + "</noembed></embed>";
                
                objsToChange[objsToChange.length] = obj;
                newObjText[newObjText.length] = outerstr;
            }
        } // for each <object>

        // now change the objects that need changing
        // NOTE: We can't do this above because it invalidates our object array
        for(var i = 0; i < objsToChange.length; ++i) {
            objsToChange[i].outerHTML = newObjText[i];
        }
        
    }
}

