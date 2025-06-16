## Database models

```mermaid

classDiagram

class Room{
- int id NN
<get/set>- string title NN
<get>- string slug NN
<get/set>- string image NN
<get/set>- string localisation NN
<get/set>- string keywords NN
<get/set>- string description NN

<get/set>- bool available NN
<get/set>- bool isReserved NN

<get/set>- User owner

<get/set>- int capacity NN
<get/set>- Equipement equipement
<get/set>- Advantage advantages 
} 


class User{
-int id
-string email
-string username
-string password
- int roles
- DateTime created_at
}

class Equipement { 
    -int id 
    -string title 
    -string slug
    -string image
}

class Advantage { 
    -int id 
    -string title 
    -string slug
    -string image
}

class Reservation{
    -User client
    -Room rentedRoom
    - DateTime reservationStart
    - DateTime reservationEnd
    -bool pending
}


User "1" -- "*" Room 
Room "*" -- "0..*" Equipement 
Room "*" -- "*" Advantage 
User "1" -- "0..*" Reservation
Room "1" -- "*" Reservation

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