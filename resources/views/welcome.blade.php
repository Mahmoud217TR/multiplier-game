<!DOCTYPE html>
<html>
<head>
    <title>Game Lobby</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body class="bg-light">
    <main class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h1 class="text-center">Game 1 Lobby</h1>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-6 justify-content-center">
                <h2 class="text-start">Players:</h2>
                <ul id='players'>
                    @foreach ($game->players as $player)
                        <li>{{ $player->name }} <b class="text-primary">({{ $player->points }})</b></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-6 justify-content-center">
                <h2 class="text-start">Latest Result: <span id='result'></span></h2>
                <ul id='guesses'>
                    
                </ul>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Connect to Pusher and listen for lobby events
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true,
        });

        const lobbyChannel = pusher.subscribe('lobby');

        lobbyChannel.bind('player-joined-1', function(data) {
            console.log('New player joined:', data);
            reloadPlayers(data);
        });

        lobbyChannel.bind('player-left-1', function(data) {
            console.log('Player left:', data);
            reloadPlayers(data);
        });

        lobbyChannel.bind('game-started-1', function(data) {
            console.log('Round result:', data);
            reloadPlayers(data);
            $('#result').html(data.multiplier);
            $('#guesses').empty();
            $.each(data.guesses, function (index, guess) { 
                if (guess.won) {
                    $('#guesses').append("<li class='alert alert-success my-2'>" + guess.player_name + " guessed " + guess.multiplier + " and Won <b>+" + (guess.reward) + "</b> Points!!</li>");
                } else {
                    $('#guesses').append("<li class='alert alert-danger my-2'>" + guess.player_name + " guessed " + guess.multiplier + " and Lost <b>-" + guess.points + "</b> Points!!</li>");
                }
            });
        });

        function reloadPlayers(data)
        {
            $('#players').empty();
            $.each(data.players, function (index, player) { 
                $('#players').append("<li>" + player.name + "<b class='text-primary'> (" + player.points + ") </b></li>");
            });
        }
    </script>
</body>
</html>