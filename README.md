# codeigniter-doctrine-integration
Setup the doctrine with codeigniter

#Step1
Copy the application\third_party\Doctrine to your application\third_party

#Step2 
Copy the application\library\Doctrine.php File to your application\library

#How to use 
Load Doctrine library 
$this->load->library("doctrine"); 
Or you can also load add "doctrine" in autoload.php 

Create Entry Object 
Set all the values to that Object

$this->doctrine->em->persist(Object);
$this->doctrine->em->flush();

Thats It... 

Enjoy the CI...


