<?php
/**
 * Class to administrate Movie database
 */
class CRMMovieAdmin
{
    private $contentDb;

    function __construct($db)
    {
        $this->contentDb = $db;
        $this->InitDb();
    }

     /**
      * Initialize database if table Content is not present. Add default data.
      *
      */
    public function InitDb()
    {
        // Check if Db holds correct tables, else crete them
        $query = "SELECT id FROM rm_movies";
        $tableExists = $this->contentDb->ExecuteQuery($query, array(), false);
        if (!$tableExists) {
            $query = <<<EOD
CREATE TABLE rm_movies
(
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    updated DATETIME,
    title VARCHAR(100) NOT NULL,
    director VARCHAR(100),
    length INT DEFAULT NULL, -- Length in minutes
    year INT NOT NULL DEFAULT 1900,
    plot TEXT, -- Short intro to the movie
    image VARCHAR(100) DEFAULT NULL,
    price INT DEFAULT 0,
    imdb VARCHAR(100) DEFAULT NULL,
    trailer VARCHAR(100) DEFAULT NULL
) ENGINE INNODB CHARACTER SET utf8
EOD;
            $res = $this->contentDb->ExecuteQuery($query, array(), false);
            // Add default content after creation of table
            $query = <<<EOD
INSERT INTO rm_movies (updated, title, director, year, plot, image, price, imdb, trailer) VALUES
  (NOW(), 'Pulp fiction', 'Quentin Tarantino', 1994, 'The lives of two mob hit men, a boxer, a gangster\'s wife, and a pair of diner bandits intertwine in four tales of violence and redemption.', 'pulp-fiction.jpg', 20, 'tt0110912', 's7EdQ4FqbhY'),
  (NOW(), 'American Pie', 'Paul Weitz', 1999, 'Four teenage boys enter a pact to lose their virginity by prom night.', 'american-pie.jpg', 5, 'tt0163651', 'Sithad108Og'),
  (NOW(), 'Pokémon The Movie 2000', 'Satoshi Tajiri, Junichi Masuda, Ken Sugimori', 1999, 'The adventures of Ash Ketchum and his partner Pikachu, who travel across many regions in hopes of being regarded as a Pokemon master.', 'pokemon.jpg', 10, 'tt0176385', 'dUaELbAqKLY' ),
  (NOW(), 'Kopps', 'Josef Fares', 2003, 'When a small town police station is threatened with shutting down because of too little crime, the police realise that something has to be done...', 'kopps.jpg', 15, 'tt0339230', 'aJFdePDqKrY'),
  (NOW(), 'From Dusk Till Dawn', 'Robert Rodriguez', 1996, 'Two criminals and their hostages unknowingly seek temporary refuge in an establishment populated by vampires, with chaotic results.', 'from-dusk-till-dawn.jpg', 24, 'tt0116367', '-bBay_1dKK8')
;
EOD;
            $this->contentDb->ExecuteQuery($query, array(), false);
            echo "Done creating Movies. ";

            // Create some extra movies
            $query = <<<EOD
            INSERT INTO rm_movies (updated, title, director, year, image, price, imdb, trailer, plot) VALUES
              (NOW(), 'Mary Poppins', 'Robert Stevenson', 1964, 'mary-poppins.jpg', 35, 'tt0058331', 'fuWf9fP-A-U', 'A magic nanny comes to work for a cold bankers unhappy family.' ),
              (NOW(), 'Tonari no Totoro', 'Hayao Miyazaki', 1988, 'Tonari-no-Totoro.jpg', 35, 'tt0096283', '92a7Hj0ijLs', 'When two girls move to the country to be near their ailing mother, they have adventures with the wonderous forest spirits who live nearby.' ),
              (NOW(), 'Der Himmel über Berlin', 'Wim Wenders', 1988, 'himmel-uber-berlin.jpg', 45, 'tt0093191', '0htOcy1QUkk', 'An angel tires of overseeing human activity and wishes to become human when he falls in love with a mortal.' ),
              (NOW(), 'Blood Simple', 'Joel Coen, Ethan Coen', 1984, 'blood-simple.jpg', 39, 'tt0086979', 'YArfyHgKuzE', 'A rich but jealous man hires a private investigator to kill his cheating wife and her new man. But, when blood is involved, nothing is simple.' ),
              (NOW(), 'The Secret Life of Walter Mitty', 'Ben Stiller', 2013, 'the-secret-life-of-walter-mitty.jpg', 35, 'tt0359950', 'QD6cy4PBQPI', 'When his job along with that of his co-worker are threatened, Walter takes action in the real world embarking on a global journey that turns into an adventure more extraordinary than anything he could have ever imagined.' ),
              (NOW(), 'Cera una volta il West', 'Sergio Leone', 1964, 'once-upon-a-time-in-the-west.jpg', 35, 'tt0064116', 'LTcTVeShSV8', 'Epic story of a mysterious stranger with a harmonica who joins forces with a notorious desperado to protect a beautiful widow from a ruthless assassin working for the railroad.' ),
              (NOW(), 'Iron Man', 'Jon Favreau', 2008, 'iron-man.jpg', 35, 'tt0371746', '8hYlB38asDY', 'After being held captive in an Afghan cave, an industrialist creates a unique weaponized suit of armor to fight evil.' )
            ;
EOD;
            $this->contentDb->ExecuteQuery($query, array(), false);
            echo "Done creating extra movies. ";
        }

        // Check if genre table exists
        $query = "SELECT id FROM rm_genre";
        $tableExists = $this->contentDb->ExecuteQuery($query, array(), false);
        if (!$tableExists) {
            $query = <<<EOD
CREATE TABLE rm_genre
(
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name CHAR(20) NOT NULL -- crime, svenskt, college, drama, etc
) ENGINE INNODB CHARACTER SET utf8;
EOD;
            $res = $this->contentDb->ExecuteQuery($query, array(), false);
            // Add default content after creation of table
            $query = <<<EOD
INSERT INTO rm_genre (name) VALUES
  ('comedy'), ('romance'), ('college'),
  ('crime'), ('drama'), ('thriller'),
  ('animation'), ('adventure'), ('family'),
  ('svenskt'), ('action'), ('horror'), ('western')
;
EOD;
            $this->contentDb->ExecuteQuery($query, array(), false);
            echo "Done creating genre db. ";
        }

        $query = "SELECT idMovie FROM rm_movie2genre";
        $tableExists = $this->contentDb->ExecuteQuery($query, array(), false);
        if (!$tableExists) {
            $query = <<<EOD
CREATE TABLE rm_movie2genre
(
  idMovie INT NOT NULL,
  idGenre INT NOT NULL,
  FOREIGN KEY (idMovie) REFERENCES rm_movies (id),
  FOREIGN KEY (idGenre) REFERENCES rm_genre (id),
  PRIMARY KEY (idMovie, idGenre)
) ENGINE INNODB;
EOD;
            $res = $this->contentDb->ExecuteQuery($query, array(), false);
            // Add default content after creation of table
            $query = <<<EOD
INSERT INTO rm_movie2genre (idMovie, idGenre) VALUES
(1, 1),
(1, 5),
(1, 6),
(2, 1),
(2, 2),
(2, 3),
(3, 7),
(3, 8),
(3, 9),
(4, 1),
(4, 9),
(4, 10),
(4, 11),
(5, 4),
(5, 11),
(5, 12),
(6, 1),
(6, 9),
(7, 7),
(7, 9),
(8, 5),
(9, 4),
(9, 6),
(10, 1),
(10, 5),
(10, 8),
(11, 13),
(12, 8)
;
EOD;
            $this->contentDb->ExecuteQuery($query, array(), false);
            echo "Done creating movie2genre db. ";
        }
    }

    public function Reset()
    {
        $query = "DROP TABLE rm_movie2genre; DROP TABLE rm_genre; DROP TABLE rm_movies; ";
        $res = $this->contentDb->ExecuteQuery($query, array(), false);
        $this->InitDb();
        $out = "";
        if ($res) {
            $out .= "Succeded resetting movie db.<br>";
        } else {
            $out .= "Failed resetting movie db.<br>";
        }
        $out .= "<p><a href='rm-movies.php'>Visa alla</a></p>";
        return $out;
    }

    private function DeleteMovie2Genre($id)
    {
        $query = "DELETE FROM rm_movie2genre WHERE idMovie = ?;";
        $res = $this->contentDb->ExecuteQuery($query, array($id), false);
        return $res;
    }

    public function DeleteMovie($id)
    {
        $out = "";
        $res = $this->DeleteMovie2Genre($id);
        if ($res) {
            $out .= "Deleted genres for movie $id.<br>";
        } else {
            $out .= "Failed deleting genres for movie $id. <br>";
        }
        $query = "DELETE FROM rm_movies WHERE id = ?;";
        $res = $this->contentDb->ExecuteQuery($query, array($id), false);
        if ($res) {
            $out .= "Deleted movie $id.<br>";
        } else {
            $out .= "Failed deleting movie $id. <br>";
        }
        $out .= "<p><a href='rm-movies.php'>Visa alla</a></p>";
        return $out;
    }

    public static function GetEditLink($id)
    {
        return "<a href=\"rm-movieadmin.php?id={$id}\">Edit</a>";
    }

    public static function GetDeleteLink($id)
    {
        return "<a href=\"rm-movieadmin.php?id={$id}&delete\">Delete</a>";
    }

    /**
     * Return html form to edit content.
     *
     * @return string  with html form
     */
    public function getEditContentForm($id)
    {
        $out = "";
        $user = new CUser();
        if (!$user->IsAuthenticated()) {
        // if (false) {
            $out = "Du måste logga in för att redigera innehåll";
            $out .= $user->LoginForm();
            return $out;
        }
        if ($id == null) {
            // Creating new post since id is null
            $legend = "Skapa filmpost";
            $submit = "name='create' value='Spara'";
        } else {
            // Edit post with id
            $legend = "Redigera filmpost";
            $submit = "name='update' value='Uppdatera'";
            // $sql = '
            //   SELECT *
            //   FROM rm_movies WHERE id = ?;
            // ';
            $query = '
              SELECT
                M.*,
                GROUP_CONCAT(G.name) AS genre
              FROM rm_movies AS M
                LEFT OUTER JOIN rm_movie2genre AS M2G
                  ON M.id = M2G.idMovie
                INNER JOIN rm_genre AS G
                  ON M2G.idGenre = G.id
                WHERE M.id = ?
            ';
            $res = $this->contentDb->ExecuteSelectQueryAndFetchAll($query, array($id), false);
        }
        $title = isset($res[0]->title) ? $res[0]->title : null;
        $director = isset($res[0]->director) ? $res[0]->director : null;
        $length = isset($res[0]->length) ? $res[0]->length : null;
        $year = isset($res[0]->year) ? $res[0]->year : null;
        $plot = isset($res[0]->plot) ? $res[0]->plot : null;
        $genre = isset($res[0]->genre) ? $res[0]->genre : null;
        $image = isset($res[0]->image) ? $res[0]->image : null;
        $price = isset($res[0]->price) ? $res[0]->price : null;
        $imdb = isset($res[0]->imdb) ? $res[0]->imdb : null;
        $trailer = isset($res[0]->trailer) ? $res[0]->trailer : null;
        // TODO: Genre select list?
        // Make Title and genre mandatory. Genre needed to filter out search result.
        $out .= <<<EOD
<form method=post>
    <fieldset>
    <legend>$legend</legend>
    <input type=hidden name=id value='{$id}'/>
    <p><label>Titel:<br/><input type='text' name='title' value='$title' required/></label></p>
    <p><label>Regisör:<br/><input type='text' name='director' value='$director'/></label></p>
    <p><label>Speltid:<br/><input type='text' name='length' value='$length'/></label></p>
    <p><label>År:<br/><input type='text' name='year' value='$year'/></label></p>
    <p><label>Plot:<br/><textarea name='plot'>{$plot}</textarea></label></p>
    <p><label>Genre:<br/><input type='text' name='genre' value='$genre' required/></label></p>
    <p><label>Bild todo upload?:<br/><input type='text' name='image' value='{$image}'/></label></p>
    <p><label>Pris:<br/><input type='text' name='price' value='{$price}'/></label></p>
    <p><label>IMDb id:<br/><input type='text' name='imdb' value='{$imdb}'/></label></p>
    <p><label>Youtube id. trailer:<br/><input type='text' name='trailer' value='{$trailer}'/></label></p>
    <p class=buttons>
        <input type='submit' $submit />
        <input type='reset' value='Återställ'/>
    </p>
    </fieldset>
</form>
EOD;
        return $out;
    }

    /**
     * Sanitize content before storing to database.
     *
     * @param array $post containing content to be sanitized
     * @return array sanitized content
     */
    private function Sanitize($post)
    {
        $post['title'] = isset($post['title']) ? strip_tags($post['title']) : "NULL";
        $post['director'] = isset($post['director']) ? strip_tags($post['director']) : "NULL";
        $post['length'] = empty($post['length']) ? null : $post['length'];
        $post['year'] = isset($post['year']) ? strip_tags($post['year']) : "NULL";
        $post['plot'] = empty($post['plot']) ? null : $post['plot'];
        $post['image'] = isset($post['image']) ? strip_tags($post['image']) : "NULL";
        $post['price'] = isset($post['price']) ? strip_tags($post['price']) : "NULL";
        $post['imdb'] = isset($post['imdb']) ? strip_tags($post['imdb']) : "NULL";
        $post['trailer'] = isset($post['trailer']) ? strip_tags($post['trailer']) : "NULL";
        return $post;
    }

    private function Genre2gid($genre)
    {
        $query = "select id from rm_genre where name = ?;";
        $res = $this->contentDb->ExecuteSelectQueryAndFetchAll($query, array($genre), false);
        if (empty($res)) {
            return null;
        } else {
            return $res[0]->id;
        }
    }

    private function AddGenres($id, $genres)
    {
        $query = "
        INSERT INTO
        rm_movie2genre (idMovie, idGenre) VALUES
        ";
        $genres = explode(',', $genres);
        foreach ($genres as $genre) {
            // Convert genre string to genre-id, if gid found insert it.
            $gid = $this->Genre2gid($genre);
            if (null !== $gid) {
                $query .= "($id, $gid),";
            }
        }
        // or use str_repeat to set id + gid?
        $query = rtrim($query, ",");
        $query .= ";";
        $this->contentDb->ExecuteQuery($query, array(), false);
    }

    public function Create($post)
    {
        // Sanitize data
        $post = $this->Sanitize($post);
        $content = array($post['title'], $post['director'], $post['length'], $post['year'], $post['plot'], $post['image'], $post['price'], $post['imdb'], $post['trailer']);
        //Save content to db
        $query = <<<EOD
        INSERT INTO rm_movies (updated, title, director, length, year, plot, image, price, imdb, trailer) VALUES
          (NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?);
EOD;
        $this->contentDb->ExecuteQuery($query, $content, false);

        // Insert genres
        $id = $this->contentDb->LastInsertId();
        $this->Addgenres($id, $post['genre']);
        // Send user to edit page if user wants to update item
        header("Location: rm-movieadmin.php?id=$id");

    }

        /**
         * Update content in database
         *
         * @param array $post containing content to be saved
         * @return void
         */
        public function Update($content)
        {
            // Sanitize $id first.
            $id = isset($content['id']) ? strip_tags($content['id']) : "NULL";
            is_numeric($id) or die('Check: Id must be numeric.');
            // Sanitize $content
            $values = $this->Sanitize($content);
            // Clean out the associative stuff from array.
            $values = array($values['title'], $values['director'], $values['length'], $values['year'], $values['plot'], $values['image'], $values['price'], $values['imdb'], $values['trailer']);
            // Add $id to end of $values
            $values[] = $id;
            $query = <<<EOD
            UPDATE rm_movies SET
                updated = NOW(),
                title = ?,
                director = ?,
                length = ?,
                year = ?,
                plot = ?,
                image = ?,
                price = ?,
                imdb = ?,
                trailer = ?
            WHERE
                id = ?
EOD;
            $this->contentDb->ExecuteQuery($query, $values, false);

            // Update genre<->movie mapping
            // ...but first delete the old genres.
            $this->DeleteMovie2Genre($id);
            $this->Addgenres($id, $content['genre']);
            // Add error handling?

            // After creation of new movie, send user to single movie presentation.
            header("Location: rm-movie.php?id=$id");
        }


}
