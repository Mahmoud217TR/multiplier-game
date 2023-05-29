<!DOCTYPE html>
<html>
<head>
    <title>Game Lobby</title>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Connect to Pusher and listen for lobby events
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true,
        });

        const lobbyChannel = pusher.subscribe('lobby');

        lobbyChannel.bind('player-joined', function(data) {
            // Handle player joined event
            console.log('New player joined:', data);
            reloadPlayers(data);
        });

        lobbyChannel.bind('player-left', function(data) {
            // Handle player left event
            console.log('Player left:', data);
            reloadPlayers(data);
        });

        lobbyChannel.bind('game-started', function(data) {
            // Handle player left event
            console.log('Round result:', data);
            reloadPlayers(data);
            $('#result').html(data.multiplyer);
            $('#guesses').empty();
            $.each(data.guesses, function (index, guess) { 
                if (guess.won) {
                    $('#guesses').append("<li>" + guess.player_name + " (+" + (guess.multiplyer*guess.points) + ") </li>");
                } else {
                    $('#guesses').append("<li>" + guess.player_name + " (0) </li>");
                }
            });
        });

        function reloadPlayers(data)
        {
            $('#players').empty();
            $.each(data.players, function (index, player) { 
                $('#players').append("<li>" + player.name + " (" + player.points + ") </li>");
            });
        }
    </script>
</head>
<body>
    <h1>Game Lobby</h1>
    <h2>Players:</h2>
    <ul id='players'>
        @foreach ($game->players as $player)
            <li>{{ $player->name }} ({{ $player->points }})</li>
        @endforeach
    </ul>
    <h3>
        Latest Result: <span id='result'></span>
        <ul id='guesses'>

        </ul>
    </h3>
</body>
</html>