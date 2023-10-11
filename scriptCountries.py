import requests
import json

def translate(champ, langue_source='en', langue_cible='fr'):
    '''
    ATTENTION : API longue... (2/3minutes pour tout le fichier)
    '''
    url = 'https://translate.googleapis.com/translate_a/single'
    params = {
        'client': 'gtx',
        'sl': langue_source,
        'tl': langue_cible,
        'dt': 't',
        'q': champ
    }
    response = requests.get(url, params=params)
    result = json.loads(response.text)
    return result[0][0][0]


def translateContinent(continent):
    if(continent == 'Africa'):
        return 'Afrique'
    elif(continent == 'Americas'):
        return 'Amérique'
    elif(continent == 'Asia'):
        return 'Asie'
    elif(continent == 'Oceania'):
        return 'Océanie'
    else:
        return 'Europe'

def createSubContinent(subContinent):
    # Amérique
    if(subContinent=='Central America'):
        return 'Amérique Centrale'
    elif(subContinent=='South America'):
        return 'Amérique du Sud'
    elif(subContinent=='North America'):
        return 'Amérique du Nord'
    elif(subContinent=='Caribbean'):
        return 'Caraïbes'

    #Europe
    elif(subContinent=='Northern Europe'):
        return 'Europe du Nord'
    elif(subContinent=='Western Europe'):
        return "Europe de l'Ouest"
    elif(subContinent=='Central Europe'):
        return 'Europe Centrale'
    elif(subContinent=='Eastern Europe'):
        return "Europe de l'Est"
    elif(subContinent=='Southeast Europe'):
        return 'Europe du Sud-Est'
    elif(subContinent=='Southern Europe'):
        return 'Europe du Sud'
    
    #Océanie
    elif(subContinent=='Polynesia'):
        return 'Polynésie'
    elif(subContinent=='Micronesia'):
        return "Micronésie"
    elif(subContinent=='Melanesia'):
        return 'Mélanésie'
    elif(subContinent=='Australia and New Zealand'):
        return "Australie et Nouvelle Zélande"

    #Asie
    elif(subContinent=='Southern Asia'):
        return 'Asie du Sud'
    elif(subContinent=='Western Asia'):
        return "Asie de l'Ouest"
    elif(subContinent=='South-Eastern Asia'):
        return 'Asie du Sud-Est'
    elif(subContinent=='Eastern Asia'):
        return "Asie de l'Est"
    elif(subContinent=='Central Asia'):
        return "Asie Centrale"
    
    #Afrique
    elif(subContinent=='Middle Africa'):
        return 'Afrique Centrale'
    elif(subContinent=='Western Africa'):
        return "Afrique de l'Ouest"
    elif(subContinent=='Southern Africa'):
        return 'Afrique du Sud'
    elif(subContinent=='Northern Africa'):
        return "Afrique du Nord"
    elif(subContinent=='Eastern Africa'):
        return "Afrique de l'Est"
    
    else:
        print("ERROR")
        return -1


def requeteCountries(translateAPI=False):
    # Attention, la traduction peut prendre plusieurs minutes
    print("Traduction  = ", translateAPI)
    
    # Requête vers le site https://restcountries.com/
    # Condition : Pays indépendent
    # Filtre : cca3,name,capital,currencies,population,languages,flags,translations,region,subregion (=code ISO 3166-1 alpha-3)
    # Données datant de 2019 environ 
    url = "https://restcountries.com/v3.1/independent?status=true&fields=name,capital,currencies,population,languages,flags,translations,cca3,region,subregion"
    response = requests.get(url)
    newCountries = {}

    i = 0
    
    if response.status_code == 200:
        data = response.json()

        # On modifie le fichier pour avoir notre propre format.
        for country in data:
            i +=1
            print(i)   
            #On recrée notre pays dans newCountry.
            newCountry = {}  

            #On fait les attributions champs par champs
            newCountry['nom'] = country['translations']['fra']['common'] 
            if(translateAPI):
                newCountry['capitale'] = translate(country['capital'][0])
            else:
                newCountry['capitale'] = country['capital'][0]
            newCountry['continent'] = translateContinent(country['region'])
            newCountry['sous-continent'] = createSubContinent(country['subregion'])
            newCountry['population'] = country['population']

            # Gestion des langues, il peut y en avoir plusieurs par pays
            listLangue = []
            if(translateAPI):
                for langue in country['languages'].values():
                    listLangue.append(translate(langue))
            else:
                for langue in country['languages'].values():
                    listLangue.append(langue)
            newCountry['langue'] = listLangue 

            # Gestion des monnaies, il peut y en avoir plusieurs par pays
            listMonnaie = []
            if(translateAPI):
                for monnaie in country['currencies'].values():
                    listMonnaie.append(translate(monnaie['name']))
            else:
                for monnaie in country['currencies'].values():
                    listMonnaie.append(monnaie['name'])
            newCountry['devise'] = listMonnaie                 

            newCountry['drapeau'] = country['flags']['svg']

            # A décommenter si on veut les noms de pays en plusieurs langues
            # newCountry['translations'] = country['translations']

            # On ajoute une clé initial au pays qui sera son code ISO-3.
            newCountries[country['cca3']] = newCountry

        print("Nombre de pays : ", len(newCountries))
        with open('./database/world/countriesApi2.json', 'w', encoding="utf-8") as f:
            json.dump(newCountries, f, indent=4, ensure_ascii=False)
    else:
        print("Error fetching data from REST Countries API")

def changeSVGtoPNG():
    with open('./database/world/countriesApi.json') as mon_fichier1:
        countriesApi = json.load(mon_fichier1)
    i=0
    for country in countriesApi:
        i+=1
        print(i)
        url = "https://restcountries.com/v3.1/alpha?codes="+country+"&fields=flags"
        response = requests.get(url)

        if response.status_code == 200:
            data = response.json()
            countriesApi[country]["drapeau"] = data[0]["flags"]["png"]

        else:
            print("ERROR : " + country)

    with open('./database/world/countriesApi2.json', 'w', encoding="utf-8") as f:
            json.dump(countriesApi, f, indent=4, ensure_ascii=False)


        

if __name__ == '__main__':
    traduction = True
    #requeteCountries(traduction)
    changeSVGtoPNG()


