from random import randint

names = ['Myles Shepard',
         'Ryland Valenzuela',
         'Gerardo Fuller',
         'Susan Prince',
         'Yusuf Guzman',
         'Giovanni Murillo',
         'Trent Hardy',
         'Donald Stein',
         'Nia Mata',
         'Amaris Hamilton',
         'Nola Ferguson',
         'Aniyah Salas',
         'Audrina Raymond',
         'Chase Woods',
         'Saul Conner',
         'Gaige Porter',
         'Francisco Gentry',
         'Trey Odom',
         'Makenna Bautista',
         'Aimee Hopkins',
         ]

amount_of_docs = 2

for name in names:
    id = randint(1000000, 9999999)
    firstname = name[:name.find(" ")]
    lastname = name[name.find(" ") + 1:]
    print(
        f'INSERT INTO Patients(national_id, first_name, last_name, email, doctor_uid) VALUES ({id}, "{firstname}", "{lastname}", "{firstname + str(id)[:-4] + "@gmail.com"}", {randint(1, amount_of_docs)});')
