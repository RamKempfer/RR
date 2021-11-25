import random
import time
import sys
#Recursion


Greetings = ["Why hello there.", "Hello there,"]
Second = ["It's a pleasure to make your aqquaintance, ","Nice to meet you, ","Pleasure to meet you, "]
american = ["American","american","united states","united States","merica","United States","America",
                    "Not European","Not European ","not european","American made","American made ","american made",
                     "american made "]
european = ["European","european","European ","european ","europe", "not america ","Note America","Not america",
            "Not American","Not american","not American","Not American ","Not american ","not American ",
            "European made","European made ","european made","european made "]
japanese = ["The japanese","The Japanese","the japanese","The japanese ","The Japanse ", "the japanese ",
            "Japanese","Japanese ","japanese","japanese ","Nihon","Nihon ","Nihonjin","Nihonjin ",
            "nihonjin "]
compliant = ["YEAH!!!","YEAH!","YEAH!!","YEAH!!!!","yes","Yes", "Sure why not", "sure why not", "sure", "Sure",
            "Sounds good","sounds good", "Sounds good to me", "sounds good to me", "yeah",
            "yeah sure", "Yeah sure", "Yeah", "alright","Alright","ok","Ok",
            "OK","okay","Okay","kk","yeet","Yeet","Yerr","yerr", "sounds good ",
            "Sounds good ","yeh","im sure", "i'm sure","I'm sure","Im sure", "im sure ", "i'm sure ","I'm sure ",
            "Im sure ","yeah im sure", "Yeah im sure", "Yeah, im sure","yeah i'm sure","Yeah sure","Yeah sure ",
            "fair enough", "fair enough ",]
noncompliant = [""," ","nah", "no", "negative", "NO", "No", "Nah","No i don't","no I don't","No I dont","No i dont",
                "Not really", "not really", "NOT REALLY", "no ", "No ","NAH ",
                "NAHH","NOOOO","NAH!"]
americar = ["Jeep", "GMC", "RAM", "Telsa Motors", "Lincoln", "Ford", "Buick",
            "Chrysler", "Cadillac", "Dodge"]
europecar = ["Aston Martin","Ferrari","Volkswagon","VW","Audi","Alfa Romeo",
            "BMW","Mercedes","Mercedes-Benz"]
japancar = ["Daihatsu","Nissan","Toyota","Isusu","Mitsubishi","Infiniti","Subarus",
            "Lexus","Acura","Mazda","Honda","Hino","Datsun"]
def Topic2():
    print_slow("TB: Awesome!\n ")
    print_slower("TB: So here's the deal, ")
    time.sleep(.1)
    print_slower("i'll be the one asking the questions, Ok?")
    answer = raw_input("\n(User) ")
    if answer in compliant:
        print_slow("TB: Swell, let's begin!\n")
    else:
        print_slower("TB: Oh, well, that sucks...")
        time.sleep(.1)
        print_slow("I was looking forward to having a nice discussion with you.\n")
        print_slower("TB: oh, well.....Bye!")
def Introduction(str):
    print_slow(str)
    user_Name = raw_input("(User) ")
    if user_Name in noncompliant:
            print_slower("Please enter a valid name: \n")
            user_Name = raw_input("(User) ")
            if user_Name in noncompliant:
                print_slower("Please enter a valid name: \n")
                user_Name = raw_input("(User) ")
                if user_Name in noncompliant:
                    print_slower("SYSTEM EXITING....\n")
                    sys.exit()
    else:
        taste = random.choice(Second)
        print_slow(taste)
        print_slow(user_Name)
        print_slower("\nTB: My name is TB.")
        time.sleep(1)
def Topic(str):
    print_slow(str)
    response = raw_input("(User) ")
    if Topic_nothing(response) == 0:
        print_slower("TB: So is that a yes?...\n")
        resp = raw_input("(User) ")
        if resp.lower() in noncompliant:
            print_slower("TB: .....If you say so.\n")
        elif resp in cmpliant:
            print_slow("TB: Ok, so cars it is?\n")
            Conversation("TB: Do you like American, European or Japanese cars?\n")
    elif response.lower() in compliant:
        print_slow("TB: Ok, awesome, ")
        print_slower("before we start, ")
        print_slower("here are a few guidelines\n")
        time.sleep(.1)
        print_slower("TB: I'll be the one asking the questions, Ok?")
        answer = raw_input("\n(User) ")
        if answer.lower() in compliant:
            print_slow("TB: Swell, let's begin!\n")
            Conversation("TB: Do you like American made, European made or Japanese manufactured cars?\n")
        else:
            print_slower("TB: Oh, well, that sucks...")
            time.sleep(.1)
            print_slow("I was looking forward to having a nice discussion with you.\n")
            print_slower("TB: oh, well.....Bye!\n")

    elif response in noncompliant:
        print_slow("TB: Oh, you don't?\n")
        resp3 = raw_input("(User) ")
        if resp3.lower() in compliant:
            time.sleep(2)
            print_slow("TB: Well, that's the only")
            print_slower(" thing that I want to talk about right now ")
            time.sleep(1.5)
            print_slower("..so, are you sure that you don't to talk about cars?..\n")
            lastchance = raw_input("(User) ")
            if lastchance.lower() in compliant:
                print_slow("TB: Alright, well if you do wanna talk about cars, you know where to find me....\n")
            else:
                print_slow("Tb: ......you need to make up your mind.\n")
                Conversation("TB: Do you like American cars or European cars?\n")
        elif resp3.lower() in noncompliant:
            print_slow("TB: ......You need to make up your mind.\n")
            Conversation("TB: Do you like American cars or European cars?\n")
def Topic_nothing(str):
    if (str) == (" "):
        return 0
    elif (str) == (""):
        return 0
    elif (str) == ("nothing"):
        return 0
    elif (str) == ("Nothing"):
        return 0
def print_slow(str):
    for letter in str:
        sys.stdout.write(letter)
        sys.stdout.flush()
        time.sleep(0.07)
def print_slower(str):
    for letter in str:
        sys.stdout.write(letter)
        sys.stdout.flush()
        time.sleep(0.09)
def Wname(str):
    if (str) == (""):
        return 0
    elif (str) == (" "):
        return 0
    elif (str) == ("I don't have a name"):
        return 0
    elif (str) == ("i don't have a name"):
        return 0
    elif (str) == ("nothing"):
        return 0
    elif (str) == ("Nothing"):
        return 0
    elif (str) == ("I dont have a name"):
        return 0
    elif (str) == ("i dont have a name"):
        return 0
def Conversation(str):
    print_slower(str)
    response = raw_input("(User) ")
    if response.lower() in american:
        time.sleep(.5)
        print_slow("TB: Oh American huh?\n")
        time.sleep(1.4)
        American("TB: So which would you say is your favorite American car brand?\n")
    elif response.lower() in european:
        time.sleep(.5)
        print_slow("TB: Oh so the Euorpeans huh?\n")
        time.sleep(1.3)
        European("TB: So which would you say is your favorite European car brand?\n")
    elif response.lower() in japanese:
        time.sleep(.5)
        print_slow("TB: So the Japanese huh?\n")
        time.sleep(1.1)
        Japan("TB: So which would you say is your favorite Japanese car brand?\n")
    else:
        time.sleep(1.5)
        print_slow("TB: Im sorry, it doesn't seem like you're sticking to the topic of cars...")
def European(str):
    print_slow(str)
    print_slower("TB: My favorite is ")
    europe = random.choice(europecar)
    print_slower(europe)
    print("\n")
    favorite = raw_input("(User) ")
def American(str):
    print_slower(str)
    print_slow("TB: My favorite is ")
    merica = random.choice(americar)
    print_slow(merica)
    print("\n")
    favorite = raw_input("(User) ")
def Japan(str):
    print_slow(str)
    print_slow("TB: My favorite is ")
    nihon = random.choice(japancar)
    print_slower(nihon)
    print("\n")
    favorite = raw_input("(User) ")



Introduction("Please enter a preferable name: \n")
Topic("\nTB: Hey, wanna talk about cars?\n")
