
// provide data in the DOT language

// Replaces all instances of the given substring.
String.prototype.replaceAll = function(
  strTarget, // The substring you want to replace
  strSubString // The string you want to replace in.
  ){
  var strText = this;
  var intIndexOfMatch = strText.indexOf( strTarget );
   
  // Keep looping while an instance of the target string
  // still exists in the string.
  while (intIndexOfMatch != -1){
  // Relace out the current instance.
  strText = strText.replace( strTarget, strSubString )
   
  // Get the index of any next matching substring.
  intIndexOfMatch = strText.indexOf( strTarget );
  }
   
  // Return the updated string with ALL the target strings
  // replaced out with the new substring.
  return( strText );
}

//abcd
var DOTstrings = document.getElementById('DOTstring').value;
plotNetwork(DOTstrings);

function getDot(id){
    var DOTstringss = document.getElementById(id).value;
    console.log(DOTstringss);
    plotNetwork(DOTstringss);
}


function plotNetwork(DOTstring){
  var container = document.getElementById('mynetwork');

  DOTstring = DOTstring.replaceAll("|", '"');

  console.log(DOTstring);

  var parsedData = vis.network.convertDot(DOTstring);

  var data = {
    nodes: parsedData.nodes,
    edges: parsedData.edges
  }

  var options = parsedData.options;

  // you can extend the options like a normal JSON variable:
  options.nodes = {
    color: {
        border: 'red',
        background: 'pink',
        highlight: {
          border: '#2B7CE9',
          background: '#D2E5FF'
        },
        hover: {
          border: 'blue',
          background: '#D2E5FF'
        }
      },

      size: 25,

      shadow:{
        enabled: true,
        color: 'rgba(0,0,0,0.5)',
        size:10,
        x:5,
        y:5
      },

  },

  options.edges = {
      smooth: {
        enabled: true,
        type: "dynamic",
        roundness: 0.5
      },

       title:"authors"
  }


// create a network
  var network = new vis.Network(container, data, options);
}



  