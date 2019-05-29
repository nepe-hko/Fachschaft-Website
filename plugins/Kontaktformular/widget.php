<?php
/*
class symbol_widget extends WP_Widget {

	function symbol_widget() {
		// Instantiate the parent object
        parent::__construct( false, 'Kontaktformularsymbol' );
	}

	function widget( $args, $instance ) {
        // Widget output
        echo '<p id="brief" style="font-size: 100%; 
                            vertical-align: top; 
                            text-align: right;
                            margin: 2px 2px;"
              >&#9993; Eine Nachricht an uns!</p>';

              ?>
              <form id="formId" action="send.php" method="POST" class="ajax">  
                        <p>Schreibe eine Nachricht an uns!</p>
                        <input type="text" name="name" id="name" placeholder="Vor- und Nachname">
                        <input type="email" name="mail" id="mail" placeholder="Deine E-Mail-Adresse" required>
                        <input type="text" name="subject" id="subject" placeholder="Betreff">			
                        <textarea id="message" name="message" placeholder="Deine Nachricht..."></textarea>
                        <div id="answer"></div>
                        <button type="submit" id="submit">Absenden!</button>	
                    </form>
             <?php
        
	}

	function update( $new_instance, $old_instance ) { 
                // Save widget options
                // wenn man im Backend was in der Form einträgt und speichert ist hier die funktion zuständig
	}

	function form( $instance ) {
        // Hier kann man was programmieren das dann im Backend vom Widget in der Funktion Kontaktformular
        // (nachdem man es zb in den footer gezogen hat erscheint        
        echo '<h4>Dies verlinkt auf die Kontaktformular-Seite</h4>';
	}
}

function kontaktformular_symbol_widget() {
	register_widget( 'symbol_widget' );
}
//hook in funktion
add_action( 'widgets_init', 'kontaktformular_symbol_widget' );


*/
?>