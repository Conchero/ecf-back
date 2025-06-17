## Database models

```mermaid

classDiagram

class Room{
- int id NN
- string title NN
- string slug NN

- string image NN
- string localisation NN
- string keywords NN
- string description NN
- int capacity NN

- bool available NN
- User owner NN

-
} 


class User{
-int id NN
-string email NN
-string phoneNumber NN
-string firstName NN
-string lastName NN
-string password NN
- int roles NN
- DateTime created_at
}

class Equipement { 
    -int id NN
    -string title NN 
    -string slug NN
}

class Software { 
    -int id NN
    -string title NN 
    -string slug NN
}

class Advantage { 
    -int id NN
    -string title NN 
    -string slug NN
}


class Reservation{
    -User client NN
    -Room rentedRoom NN
    - DateTime reservationStart NN
    - DateTime reservationEnd NN
    - bool pending
}


class RoomEquipement{
    Room assignedRoom NN
    Equipement assignedEquipement NN
}

class RoomSoftware{
    Room assignedRoom NN
    Software assignedSoftware NN
}

class RoomAdvantage{
    Room assignedRoom NN
    Advantage assignedAdvantage NN
}

User "1" -- "*" Room 
User "1" -- "0..*" Reservation
Room "1" -- "*" Reservation
Room "1" -- "*" RoomEquipement
Room "1" -- "*" RoomSoftware
Room "1" -- "*" RoomAdvantage
Equipement "1" -- "*" RoomEquipement
Software "1" -- "*" RoomSoftware
Advantage "1" -- "*" RoomAdvantage



```


## Controller

```mermaid

classDiagram

class RoomController{
    -create()
    -edit()
    -delete()
}

class ReservationController{
    -create()
    -validate()
    -edit()
    -delete()
}

class UserController{
    -newPassword()
    -profile()
    -delete()
    -myData()
}

class SecurityController{
    -register()
    -login()
    -logout()
}

class EquipementController{
    -create()
    -edit()
    -delete()
}

class AdvantageController{
    -create()
    -edit()
    -delete()
}

```

# Services

```mermaid
classDiagram

     class ImageService {
    -upload(UploadedFile image)
    -compress(UploadedFile image)
    -delete(string slug)
  }

  class ReservationService {
    -send5daysLeftReservationAlert()
  }

    class ModerationService {
    -approve(Reservation reservation)
    -reject(Reservation reservation)
  }


```


# Event Listener

```mermaid

classDiagram


class ReservationListener{
    -onReservationCreated()
    -onReservationValidated()
    -onReservationDeleted()
    -onReservationUpdated()
}

class UserListener{
       -onUserLogin()
    -onUserValidated()
}