## Database models

```mermaid

classDiagram

class Room{
- int id NN
- string title NN
- string slug NN

- string image NN
- string localisation NN
- int capacity NN
- text keywords 
- text description 

- bool available NN
- User owner NN

-Equipment equipments
-Software softwares
-Advantage advantages

-Reservation reservations
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

-Reservation reservations
}

class Equipment { 
    -int id NN
    -string title NN 
    -string slug NN
    -Room rooms
}

class Software { 
    -int id NN
    -string title NN 
    -string slug NN
    -Room rooms
}

class Advantage { 
    -int id NN
    -string title NN 
    -string slug NN
    -Room rooms
}


class Reservation{
    - int id NN
    - string slug
    - User client NN
    - Room rentedRoom NN
    - DateTime reservationStart NN
    - DateTime reservationEnd NN
    - bool pending
}


class Notification{
    - int id NN
    - User receiver NN
    - Reservation reservation NN
    - string message
    - string slug 
    - bool is_read
}


User "1" -- "*" Room 
User "1" -- "*" Reservation
Room "1" -- "*" Reservation
Room "*" -- "*" Equipment
Room "*" -- "*" Software
Room "*" -- "*" Advantage
Reservation "1" -- "*" Notification
Notification "*" -- "1" User





```


## Controller

```mermaid

classDiagram

class RoomController ~~Easy Admin~~{
    -create()
    -edit()
    -delete()
}

class ReservationController ~~Easy Admin~~{
    -create()
    -validate()
    -edit()
    -delete()
}

class UserController~~Easy Admin~~{
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

class EquipementController~~Easy Admin~~{
    -create()
    -edit()
    -delete()
}

class AdvantageController~~Easy Admin~~{
    -create()
    -edit()
    -delete()
}


```

# Services

```mermaid
classDiagram

     class ImageService ~~Easy Admin~~{
    -upload(UploadedFile image)
    -compress(UploadedFile image)
    -delete(string slug)
  }

  class ReservationAlertService {
    -send5daysLeftReservationAlert()
  }

    class ReservationManagementService {
    -approve(Reservation reservation)
    -reject(Reservation reservation)
  }


    class NotificationService {
        createNotification(Reservation _res,string message)
        sendNotification(User receiver)
        checkNotificationArrivedToCorrectUser()
        readNotification(string slug)
        delete(string slug )
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