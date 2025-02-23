<head>

    <title>Proposer un trajet</title>

</head>

<form action="" method="POST">
    <label for="travelDate">Date du départ</label>
    <input type="date" id="travelDate" name="travelDate" required>
    <label for="travelDepartureCity">Ville de départ : </label>
    <input type="text" id="travelDepartureCity" name="travelDepartureCity" required>
    <label for="travelArrivalCity">Ville d'arrivée </label>
    <input type="text" id="travelArrivalCity" name="travelArrivalCity" required>
    <label for="travelDepartureTime">Heure de départ : </label>
    <input type="time" id="travelDepartureTime" name="travelDepartureTime">
    <label for="travelArrivalTime">Heure d'arrivée : </label>
    <input type="time" id="travelArrivalTime" name="travelArrivalTime">
    <label for="travelPrice">Prix pour une personne : </label>
    <input type="int" id="travelPrice" name="travelPrice">
    <label for="placesOffered">Nombre de places proporées : </label>
    <input type="int" id="placesOffered" name="placesOffered">
    <label for="carID">Voiture : </label>
    <input type="text" id="carID" name="carID"><!--put a drop list with driver's cars-->
    <input type="submit" value="Proposer le trajet">
</form>

