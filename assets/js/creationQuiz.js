function showQCMOptions(){
    document.getElementById("champReponse").hidden = false
    document.getElementById("champQuestion").hidden = false
    document.getElementById("champNbProp").hidden = false
    document.getElementById("champLoc").hidden = true
}

function showLocalisationOptions(){
    document.getElementById("champReponse").hidden = true
    document.getElementById("champQuestion").hidden = true
    document.getElementById("champNbProp").hidden = true
    document.getElementById("champLoc").hidden = false
}

function updateForm(e){
    var val = e.value
    console.log(val)
    if( val === "qcm"){
        showQCMOptions()
    }
    if ( val === "loc"){
        showLocalisationOptions()
    }
}