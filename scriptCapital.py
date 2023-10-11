import json

class bcolors:
    HEADER = '\033[95m'
    OKBLUE = '\033[94m'
    OKCYAN = '\033[96m'
    OKGREEN = '\033[92m'
    WARNING = '\033[93m'
    FAIL = '\033[91m'
    ENDC = '\033[0m'
    BOLD = '\033[1m'
    UNDERLINE = '\033[4m'

with open('./database/country-capitals.json', encoding="utf-8") as mon_fichier1:
    dataCapital = json.load(mon_fichier1)

with open('./database/world2.json') as mon_fichier2:
    dataWorld = json.load(mon_fichier2)

def checkCapitalForEachValue():
    '''
    Vérification du fichier capitals.
    Est-ce que chaque pays possède bien un nom de capital et des coordonnées associées.
    '''
    for capital in dataCapital:
        if(not("CapitalName" in capital)):
            print(bcolors.WARNING + "Pays sans capital : " + capital['CountryName'] + bcolors.ENDC)
        if(not("CapitalLatitude" in capital)):
            print(bcolors.FAIL + "Pas de coordonnées : ", capital['CountryName'] + bcolors.ENDC)
    print("Nb capitale : ", len(dataCapital))
    print("Fin checkCapitalForEachValue()")

def compare2files():
    '''
    Vérifie que tous les pays soient dans capitals.
    Vérifie que les noms de capitals soit les mêmes
    '''
    for country in dataWorld['features']:
        present = False
        for capital in dataCapital:
            if(country["properties"]["ISO_A2"] in capital['CountryCode']):
                present = True
        if(not(present)):
            print(bcolors.FAIL + "Pays non présent dans capitals : " + country["properties"]["NAME"] + bcolors.ENDC)

    print("Fin compare2files()")

def addcoordinatesInCountrisFile():
    for capital in dataCapital:
        key = capital['CountryCode']
        for country in dataWorld['features']:
            if(key in country["properties"]["ISO_A2"]):
                if("CapitalName" in capital):
                    country["properties"]["capital"] = capital['CapitalName']
                else:
                    country["properties"]["capital"] = ""
                country["properties"]["coordinatesCapital"] = [capital['CapitalLongitude'],capital['CapitalLatitude']]

    with open('./database/worldWithCapital.json', 'w', encoding="utf-8") as f:
        json.dump(dataWorld, f, indent=2, ensure_ascii=False)
    
    print("Fin addcoordinatesInCountrisFile()")


if __name__ == '__main__':
    checkCapitalForEachValue()
    compare2files()
    addcoordinatesInCountrisFile()