SELECT * FROM ex4play.videojuego
where incategvideojuego between 1 and 3 
and felanzamiento between '2017-07-02 00:00:00' and  '2017-12-31 00:00:00'; 
#and felanzamiento < '2017-07-01 00:00:00';

SELECT min(felanzamiento) FROM ex4play.videojuego;

SELECT incategvideojuego, count(*) FROM ex4play.videojuego
group by incategvideojuego;

#clasificar videojuegos de más de 1 año
update ex4play.videojuego
set incategvideojuego = 3 
where felanzamiento < '2017-07-01 00:00:00'
and incategvideojuego between 1 and 3;

#clasificar videojuegos de entre 6 meses y 1 año
update ex4play.videojuego
set incategvideojuego = 2 
where felanzamiento between '2017-07-01 00:00:00' and  '2017-12-31 00:00:00' 
and incategvideojuego between 1 and 3;

#clasificar videojuegos 6 meses o más recientes
update ex4play.videojuego
set incategvideojuego = 1 
where felanzamiento >= '2018-01-01 00:00:00' 
and incategvideojuego between 1 and 3;


