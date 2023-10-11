
function createCountryList(countries) {
    //div dans laquelle ajouter les pays
    var list = document.getElementById('countryList');

    //pour chaque pays, création d'un bouton et ajout
    for (var k in countries){
        button = createOneCountry(k, countries[k]);
        list.appendChild(button); 
    }

    //tri par ordre alphabétique
    sortDivs("countryList", ".btn-country");
}


function createOneCountry(key, country) {
    //création d'un bouton pour le pays
    var button = document.createElement('div');
        button.type = "button";
        button.value = key;
        button.name = country["nom"];
        button.className = "btn-country";

    //création dune balise image si le drapeau est défini, et ajout au bouton
    if (typeof country["drapeau"] !== 'undefined') {
        var div = document.createElement("div")
            div.className = "div-icon-flag"
        var img = document.createElement("img");
            img.src = country["drapeau"];
            img.className = "icon-flag";
        var txt = document.createElement("p");
            txt.innerHTML = button.name
        div.appendChild(img) 
        button.appendChild(div);
        button.appendChild(txt);
    }
    //nom du pays seul si pas de drapeau
    else {
        button.innerHTML = button.name;        
    }
    
    button.addEventListener("click", changeSide);
    
    return button;
}

function changeSide(e) {
    const button = e.target.closest('.btn-country');
    //appendChild déplace un élément dans l'autre liste
    if (button.parentElement.id == "countryList") {
        document.getElementById("selectedList").appendChild(button);
        sortDivs("selectedList", ".btn-country");
        count = document.getElementById("countryCount").innerHTML
        count = parseInt(count) + 1
        document.getElementById("countryCount").innerHTML = count
    }
    else if (button.parentElement.id == "selectedList") {
        document.getElementById("countryList").appendChild(button);
        sortDivs("countryList", ".btn-country")
        count = document.getElementById("countryCount").innerHTML
        count = parseInt(count) - 1
        document.getElementById("countryCount").innerHTML = count
    }
}

function getSelectedIds() {
    var idList = []
    var elements = document.getElementById("selectedList").children
    for (i = 0; i < elements.length; i++) {
        idList.push(elements[i].value)
    }
    return idList
}

function sortDivs(from, type) {
    //selectionne toutes les divs de $from avec l'attribut $type
    Array.prototype.slice.call(document.getElementById(from).querySelectorAll(type))
        .sort(function sort(ea, eb) {
            //tri le tableau de div selon l'attribut name
            //return ea.name > eb.name;
            return ea.name.localeCompare(eb.name)
        })
        .forEach(function (div) {
            //déplace les élements dans la div parent
            div.parentElement.appendChild(div);
        });
}

function switchCategorieMode(e){
    var button = e;
    if (button.id == "btn-constraint") {
        document.getElementById("countryListSelection").hidden = true;
        document.getElementById("countryConstraintSelection").hidden = false;
        button.disabled = true;
        document.getElementById("btn-list").disabled = false;
        button.parentElement.setAttribute("valType","0")
    }
    if (button.id == "btn-list") {
        document.getElementById("countryListSelection").hidden = false;
        document.getElementById("countryConstraintSelection").hidden = true;
        button.disabled = true;
        document.getElementById("btn-constraint").disabled = false;
        button.parentElement.setAttribute("valType","1")
    }
}

function createConstraintBox(attributes, values){
    //liste des contraintes
    var list = document.getElementById("constraintList")

    //création constraint-box
    var box = document.createElement("div")
        box.className = "constraint-box"
    list.appendChild(box)

    //création elements
    var element1 = document.createElement("div")
        element1.className = "element"
    var element2 = document.createElement("div")
        element2.className = "row element"
    var element3 = document.createElement("div")
        element3.className = "element"
    var element4 = document.createElement("div")
        element4.className = "element"
    box.appendChild(element1)
    box.appendChild(element2)
    box.appendChild(element3)
    box.appendChild(element4)

    //select attribute
    var select = document.createElement("select")
        select.className = "form-select form-select-lg"
        select.list = values
        select.onchange = updateValuesSelection
    element1.appendChild(select)

    //default option
    var option = document.createElement("option")
        option.value = ""
        option.innerHTML = "Choisissez un attribut"
        option.selected = true
        option.hidden = true
    select.appendChild(option)

    //option list for attributes
    for (var i in attributes) {
        var option = document.createElement("option")
            option.value = attributes[i]
            option.innerHTML = attributes[i]
        select.appendChild(option)
    }

    //constraint type buttons
    var equal = document.createElement("button")
        equal.id = "btn-equal"
        equal.type = "button"
        equal.className = "btn-linked col-md-6"
        equal.disabled = true
        equal.innerHTML = "égal à"
        equal.onclick = switchConstraintType
    element2.appendChild(equal)
    element2.value = "="
    var diff = document.createElement("button")
        diff.id = "btn-diff"
        diff.type = "button"
        diff.className = "btn-linked col-md-6"
        diff.innerHTML = "différent de"
        diff.onclick = switchConstraintType
    element2.appendChild(diff)

    //select value
    var select = document.createElement("select")
        select.className = "form-select form-select-lg"
        select.id = "valueList"
    element3.appendChild(select)

    //delete constraint
    var deleteB = document.createElement("button")
        deleteB.innerHTML = "supprimer"
        deleteB.className = "btn-delete"
        deleteB.onclick = deleteConstraint
    element4.appendChild(deleteB)
}

function deleteConstraint(e) {
    var constraint = e.target.parentElement.parentElement
    constraint.remove()
}

function updateValuesSelection(e) {
    var val = e.target.value
    var list = e.target.list
    var parent = e.target.parentElement.parentElement
    var select = parent.children[2].children[0]
    //erase childs
    select.replaceChildren()

    list[val].sort()

    //create values
    for (var i in list[val]) {
        var option = document.createElement("option")
            option.value = list[val][i]
            option.innerHTML = list[val][i]
        select.appendChild(option)
    }

}

function switchConstraintType(e){
    var button = e.target;
    if (button.id == "btn-equal") {
        button.parentElement.children[1].disabled = false;
        button.parentElement.value = "="
        button.disabled = true;
    }
    else if (button.id == "btn-diff") {
        button.parentElement.children[0].disabled = false;
        button.parentElement.value = "!="
        button.disabled = true;
    }
}

function getConstraints(){
    const constraintList = document.getElementById("constraintList")
    var filtres = {}

    for (i=0; i<constraintList.children.length; i++){
        filtres[i] = {}
        const box = constraintList.children[i]
        filtres[i]["nomAttr"] = box.children[0].children[0].value
        filtres[i]["type"] = box.children[1].value
        filtres[i]["valeurAttr"] = box.children[2].children[0].value
    }

    return filtres
}

function getCategorie(){
    var cat = {}
    if(document.getElementById("btn-list").disabled){
        cat["type"] = 1
        cat["pays"] = getSelectedIds()
    }
    else if(document.getElementById("btn-constraint").disabled){
        cat["type"] = 0
        cat["filtres"] = getConstraints()
    }

    return cat
}

function simulateConstraint(countries) {
    const filters = getConstraints()
    var array = Object.values(countries)

    function applyFilter(filter) {
        return function(element) {
            if(!Array.isArray(element[filter["nomAttr"]])) {
                if (filter["type"] == "="){
                    return element[filter["nomAttr"]] == filter["valeurAttr"]
                }
                if (filter["type"] == "!="){
                    return element[filter["nomAttr"]] != filter["valeurAttr"]
                }
            }
            else {
                if (filter["type"] == "="){
                    return element[filter["nomAttr"]].includes(filter["valeurAttr"])
                }
                if (filter["type"] == "!="){
                    return !element[filter["nomAttr"]].includes(filter["valeurAttr"])
                }
            }
        }
    }

    for (var i in filters){
        array = array.filter(applyFilter(filters[i]));
    }

    array.sort(function sort(ea, eb) {
        return ea.nom > eb.nom;
    })
    
    createSimulationList(array)
    
}

function createSimulationList(countries) {
    //div dans laquelle ajouter les pays
    var list = document.getElementById('simulationList');
    list.replaceChildren()

    //pour chaque pays, création d'un bouton et ajout
    for (var k in countries){
        var country = countries[k]
        var button = document.createElement('button');
        button.type = "button"
        button.name = country["nom"];
        button.className = "btn-country";
        button.disabled = true

        //création dune balise image si le drapeau est défini, et ajout au bouton
        if (typeof country["drapeau"] !== 'undefined') {   
            var div = document.createElement("div")
                div.className = "div-icon-flag"
            var img = document.createElement("img");
                img.src = country["drapeau"];
                img.className = "icon-flag";
            var txt = document.createElement("p");
                txt.innerHTML = button.name
            div.appendChild(img) 
            button.appendChild(div);
            button.appendChild(txt);
        }
        //nom du pays seul si pas de drapeau
        else {
            button.innerHTML = button.name;        
        }
        
        list.appendChild(button); 
    }
}