# Zoom Integration for Events Manager Wordpress Plugin
100% free 3rd party child plugin for Events Manager wordpress plugin.

__Help flatten the curve: Keep people indoors & keep online events free and accessible for everyone__

__Help me: Download, use and feed back your experience to help me test and perfect it__

# Features
* Manage your Zoom link on the Edit event and edit 'Recurring Event' screens.
* Elegantly manage recurring events where each child shares the same link or where each child has it's own individual link.
* Display your Zoom link in confirmation emails and on the front-end 'Event Details' page.
* Additional placeholders to make displaying the Zoom Link elegant and easy.

# Additional Placeholders
These are all 'Event-level' placeholders so they can be used on the Event Details pages or in confirmation emails:

 * *#_BOOKINGSTATUS* - Looks at all the bookings the user has made and raggregated into one status (ie if they have one pending, one cancelled and one confirmed booking then this status will show simply 'confirmed' because they are going to the event!
 * *#_ZOOMURL* - Allows you to show the zoom url on the Event Details page or in confirmation emails.
 
 # Additional Conditional Placeholders
 Again, these are all 'Event-level' placeholders for use on event's page or in confirmation emails:
 
  * *{user_has_a_confirmed_booking}* - Does the user have at least one confirmed booking (are they confirmed on the event)
  * *{is_zoom_event}* - Is this event configured with a Zoom Link
  * *{is_zoom_event_and_user_has_a_confirmed_booking}*
  * *{is_logged_in}*
  * *{is_logged_out}*
  * *{user_has_any_booking}* - Does the user have at least one booking (any status)
  * *{user_has_booking_status_in_1_4_0}* - Here you can change these numbers to filter by the different order statuses used behind the scenes
  
 # Example Placeholder Uses
```
{is_logged_out}
<p>Please <a>login</a> to access your Zoom Link</p>
{/is_logged_out}

{user_has_any_booking}
<p>Your Booking Status is: *#_BOOKINGSTATUS*</p>
{/user_has_any_booking}

{is_zoom_event_and_user_has_confirmed_booking}
    <p>Your Zoom Link is: #_ZOOMURL </p>
{/is_zoom_event_and_user_has_confirmed_booking}
```
# Installation Instructions
 - Just download upnrunning-eventman-extras.zip above
 - Login to your wordpress wp-admin console
 - On the left hand navigation menu Click Plugins --> Add new
 - Click 'Upload Plugin' at the top
 - Select the zip file
 - Remember to contact me to tell me about your expoerience of installing & implementing - help flatten the curve!

# Disclaimer for Wordpress Developers:
100% Free - All i ask in return is that you feedback your expereince of using it and any issues you face. Even better join in and help me add new features :)

 * I havent tested on multi-site installs as yet (but no reason why it wouldn't work)
 * It's only in English at present (why not help me translate?)
 * I havent tested with a public-facing front-end 'submit your own event' form yet (but should work)
 
 # Screenshots 
Recurring Event Admin Screenshot:
![Recurring Event Admin Screenshot](/upnrunning-eventman-extras/screenshot-1.png7?raw=true "Recurring Event Admin Screenshot")

Front-end Event Listing Screenshot:
![Front-end Event Listing Screenshot](/upnrunning-eventman-extras/screenshot-2.png?raw=true "Front-end Event Listing Screenshot")

Single Event Admin Screenshot:
![Single Event Admin Screenshot](/upnrunning-eventman-extras/screenshot-3.png?raw=true "Single Event Admin Screenshot")
