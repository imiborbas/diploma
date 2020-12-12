# Model-View-Controller elvű webes keretrendszer fejlesztése bemutató wiki alkalmazással, PHP nyelven
## Borbás Imre Sándor diplomamunkája

**A következő lépésekkel lehet az alkalmazást telepíteni:**

1. A projekt public_html mappájának webről elérhetőnek kell lennie, Apache2 webkiszolgáló használatával.
2. A helyes működéshez szükséges a az Apache mod_rewrite moduljának bekapcsolása.
3. Hozzunk létre egy MySQL adatbázist a szerverünkön, lehetőség szerint utf-8 karakterkódolással.
4. Töltsük be az adatbázisba az install mappában található .sql állományokat a következő sorrendben:
  * app_ddl.sql
  * session_ddl.sql
  * test_data.sql
5. Módosítsuk a konfigurációs állományokat a config mappában:
  * propel-conf.php - a webalkalmazás adatbáziskapcsolata adható meg
  * session.yml - a munkamenet kezelés adatbáziskapcsolata adható meg
  * mail.yml - a hibakezelés e-mail küldési beállításai állíthatók be
6. A cache mappának állítsunk be olyan jogosultságot, hogy a webkiszolgálót futtató felhasználó tudja írni.
7. Próbáljuk ki az alkalmazást

**Jelszavak:**

admin:admin

developer:developer

user:user
