## About Chariot

Chariot is a command and control center for WireGuard . It provides a Rest-API and a web ui to control wireguard functionalities such as client creation and deletion,etc . Currently is extremely experimental , so please don't use it in production. 


## Assumptions

This project wraps the cli utility `wg` . So I made all the functionality with the assumption that you have properly set up a WireGuard server

## Setup
 
Chariot communicates with WireGuard server using SSH . Currently the whole setup is biased to my dev environment . I have a passwordless sudo user to run commands.

Setup the project like any Laravel project with proper env variables. And you are ready to go.
 
## Screenshots

<a href="https://ibb.co/WHb27LC"><img src="https://i.ibb.co/ZfnMv09/Capture4.png" alt="Capture4" border="0"></a>

<a href="https://ibb.co/5WqG4Sx"><img src="https://i.ibb.co/XZmjLKk/Capture3.png" alt="Capture3" border="0"></a>

