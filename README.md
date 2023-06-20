# Projet Laravel sur Authentification et Authorization

## 1/ Liste des Modules :

    - Users

    - Groups (des Users)

    - Posts 

## 2/ Authorization :

    - Création d'un group : établir les permissions (Read, Create, Update, Delete, et Capacité de Permissions) de son group

    - Créer un User : Il a tous les droits de son group

    - Les Droits :

    a/ Module Post :

    - Lire tous les Posts

    - Créer un Post

    - Modifier un Post : Il ne peut modifier que son prore Post

    - Supprimer un Post : Il ne peut supprimer que son prore Post. Utiliser Soft Delete et Les Posts des User supprimés.

    b/ Module Group:

    - Lister tous les Groups

    - Ajouter un groupe

    - Modifier et supprimer son propre

    - Possibilité d'établir les droits de l'User

    c/ Module User:

    - Lister tous les User

    - Ajouter un User

    - Modifier et supprimer un User

    - Utiliser Soft Delete : Module Restore et Destroy un User

## 3/ Authorization :

   - Gate : pour accéder à un Controller
  
   - Policy : pour chaque model

## 4/ Authentification :

    - Validation of Forms :  validator et messages d'erreurs pour les 'Inputs'

## 5/ Autres :

    - Migration : créer les tables, Foreign Keys

    - Seeder

    - Models : Etablir les relations entre les tables selon Foreign Keys

    - Search : sur les champs de sa table et de la relation table.

    - Pagination (5 items per page)

    - Ranger (sort) les Posts, Groups, Users selon Name, ou Authors, ...

## 1. Login :
    
![login](https://github.com/trong53/Laravel_Project_Authen_Authorization/assets/107623849/86c602b4-0906-4f5d-99e7-ac9c0754c111)

## 2. Inscription :

![register](https://github.com/trong53/Laravel_Project_Authen_Authorization/assets/107623849/677d4a73-7179-46cc-b6cb-3c2c00528965)

## 3. Posts :

![listPost](https://github.com/trong53/Laravel_Project_Authen_Authorization/assets/107623849/86c48da7-1f1c-4ad1-b1e9-944c3dd9000a)

![addPost](https://github.com/trong53/Laravel_Project_Authen_Authorization/assets/107623849/7c95f465-5e33-4ef4-bf73-69062d247e0a)

![trashedPost](https://github.com/trong53/Laravel_Project_Authen_Authorization/assets/107623849/de71d088-08fe-4400-9b93-c435e7ac544e)

## Posts des Users supprimés (soft Deletes) : Uniquement Administrateurs peuvent voir ces posts 

![PostsofTrashedUsers](https://github.com/trong53/Laravel_Project_Authen_Authorization/assets/107623849/1f453e33-c363-4418-9bbf-909d9700ee55)

## 4. Groups :

![listGroup](https://github.com/trong53/Laravel_Project_Authen_Authorization/assets/107623849/a0616bc8-9b7c-42fd-bacf-83bb5c18db74)

## - En cliquant sur le Bouton "Permission" : nous avons le management des permissions de ce groupe, on peut modifier les permissions. Exemples :

### a. Group Administration :

![PermissionForGroup](https://github.com/trong53/Laravel_Project_Authen_Authorization/assets/107623849/d4f4d53a-08cf-4f32-b484-a3600bc1c4a4)

### b. Group Staff :

![permissionStaff](https://github.com/trong53/Laravel_Project_Authen_Authorization/assets/107623849/67203d84-2aac-47d8-84ac-be955e5d68ec)

## 5. Users :

![ListUser](https://github.com/trong53/Laravel_Project_Authen_Authorization/assets/107623849/54256c50-f38e-4232-a33c-381084972940)

![TrashedUsers](https://github.com/trong53/Laravel_Project_Authen_Authorization/assets/107623849/f2ddca85-8f42-4d77-b93d-b2284d4ad62f)

![deleteUser](https://github.com/trong53/Laravel_Project_Authen_Authorization/assets/107623849/77d1378e-67c5-435f-a26b-26ebd54ad406)


    

