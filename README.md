# netatmo2cosm

**Get last measure from Netatmo device and send it to Cosm.**

*Cosm is now [Xively](https://xively.com/).*


## Instructions

On [Cosm](https://cosm.com/), create a Feed for each [Netatmo](http://www.netatmo.com/) module (Internal and External) and a Datastream for each sensor (Temperature, CO2, Humidity, Pressure, Noise). This script can be called via a browser or crontab. It does not display information if there is no error.

**Pre-required**:

* the file `Netatmo-API-PHP/NAApiClient.php` from [Netatmo/Netatmo-API-PHP · GitHub](https://github.com/Netatmo/Netatmo-API-PHP)
* Replace all `YOUR_*` variables with your own information in `netatmo2cosm.php` file.

## Example

* [Cosm - orx57's Console](https://cosm.com/users/orx57)
