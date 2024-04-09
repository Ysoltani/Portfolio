import tkinter as tk
from tkinter import messagebox  # Importer la boîte de dialogue
from PIL import Image, ImageTk
import mysql.connector

class DatabaseHandler:
    def __init__(self):
        self.logged_in = False
        self.mydb = None
        self.mycursor = None

    def connect(self, username, password):
        try:
            self.mydb = mysql.connector.connect(
                host="localhost",
                user=username,
                password=password,
                database="shoesport"
            )
            self.mycursor = self.mydb.cursor(buffered=True)
            self.logged_in = True
        except mysql.connector.Error as err:
            print(f"Erreur lors de la connexion à la base de données : {err}")

    def close_connection(self):
        if self.mycursor:
            self.mycursor.close()
        if self.mydb:
            self.mydb.close()

class MyApp:
    def __init__(self, root):
        self.root = root
        self.root.title("SAV")
        self.root.configure(bg="#f0b27b")  # Changement de couleur de fond
        self.db_handler = DatabaseHandler()

        self.connexion_frame()

    def connexion_frame(self):
        self.frame_connexion = tk.Frame(self.root, bg="white", borderwidth=5, relief=tk.RIDGE, padx=20, pady=20, bd=10, highlightbackground="black")
        self.frame_connexion.pack(expand=True)

        label_username = tk.Label(self.frame_connexion, text="Nom d'utilisateur:", bg='white') # Changer la couleur du texte
        label_username.grid(row=0, column=0, sticky="e")

        self.entry_username = tk.Entry(self.frame_connexion, width=20, bg='white') # Changer la couleur du fond
        self.entry_username.grid(row=0, column=1, padx=10, pady=5)

        label_password = tk.Label(self.frame_connexion, text="Mot de passe:", bg='white') # Changer la couleur du texte
        label_password.grid(row=1, column=0, sticky="e")

        self.entry_password = tk.Entry(self.frame_connexion, show="*", width=20, bg='white') # Changer la couleur du fond
        self.entry_password.grid(row=1, column=1, padx=10, pady=5)

        # Chargement de l'image et redimensionnement
        image = Image.open("imagerobot.jpg")
        image = image.resize((100, 100))
        self.photo = ImageTk.PhotoImage(image)

        label_image = tk.Label(self.frame_connexion, image=self.photo)
        label_image.grid(row=0, column=2, rowspan=2, padx=10, pady=5)

        bouton_connexion = tk.Button(self.frame_connexion, text="Se connecter", command=self.valider_connexion, width=20, bg="orange", borderwidth=4, relief=tk.GROOVE)
        bouton_connexion.grid(row=2, columnspan=2, pady=10)

        self.label_message = tk.Label(self.frame_connexion, text="", bg='white') # Changer la couleur du texte
        self.label_message.grid(row=3, columnspan=2)

    def valider_connexion(self):
        username = self.entry_username.get()
        password = self.entry_password.get()

        self.db_handler.connect(username, password)

        if self.db_handler.logged_in:
            self.label_message.config(text="")
            self.frame_connexion.destroy()
            self.afficher_infos_base_de_donnees()
            self.afficher_infos_utilisateurs()
            self.afficher_infos_produits()
        else:
            self.label_message.config(text="Nom d'utilisateur ou mot de passe incorrect", bg='white') # Changer la couleur du texte

    def envoyer_reponse(self, message_id, entry_reponse):
        reponse = entry_reponse.get()
        # Boîte de dialogue pour confirmation
        confirmation = messagebox.askyesno("Confirmation", "Voulez-vous vraiment envoyer cette réponse ?")
        if confirmation:
            try:
                self.db_handler.mycursor.execute("UPDATE messages SET messageversclient = %s WHERE id = %s", (reponse, message_id))
                self.db_handler.mydb.commit()
                print("Réponse envoyée avec succès à la base de données.")
                messagebox.showinfo("Message envoyé", "La réponse a été envoyée avec succès.")
            except mysql.connector.Error as err:
                print(f"Erreur lors de l'envoi de la réponse à la base de données : {err}")

    def afficher_infos_base_de_donnees(self):
        try:
            self.db_handler.mycursor.execute("SELECT id, messagesversvendeur, utilisateur_id, ID_PRODUIT FROM messages")
            infos_messages = self.db_handler.mycursor.fetchall()

            self.messages_frame = tk.Frame(self.root, bg="white", borderwidth=5, relief=tk.RIDGE, padx=20, pady=20, bd=10, highlightbackground="black")
            self.messages_frame.pack(expand=True)

            # Affichage des messages
            for i, info in enumerate(infos_messages):
                message_id = info[0]

                label_id = tk.Label(self.messages_frame, text=f"ID:", bg='white')
                label_id.grid(row=i, column=0, sticky="w")

                id_content = tk.Label(self.messages_frame, text=message_id, bg='white')
                id_content.grid(row=i, column=1, sticky="w")

                label_message = tk.Label(self.messages_frame, text=f"Message:", bg='white')
                label_message.grid(row=i, column=2, sticky="w")

                message_content = tk.Label(self.messages_frame, text=info[1], bg='white')
                message_content.grid(row=i, column=3, sticky="w")

                label_utilisateur_id = tk.Label(self.messages_frame, text="Utilisateur ID:", bg='white')
                label_utilisateur_id.grid(row=i, column=4, sticky="w")

                utilisateur_id_content = tk.Label(self.messages_frame, text=info[2], bg='white')
                utilisateur_id_content.grid(row=i, column=5, sticky="w")

                label_produit_id = tk.Label(self.messages_frame, text="Produit ID:", bg='white')
                label_produit_id.grid(row=i, column=6, sticky="w")

                produit_id_content = tk.Label(self.messages_frame, text=info[3], bg='white')
                produit_id_content.grid(row=i, column=7, sticky="w")

                # Champ d'entrée pour la réponse
                entry_reponse = tk.Entry(self.messages_frame, width=20, bg='white') # Changer la couleur du fond
                entry_reponse.grid(row=i, column=8, padx=10, pady=5)

                # Bouton pour envoyer la réponse
                bouton_envoyer = tk.Button(self.messages_frame, text="Envoyer", command=lambda msg=message_id, entry=entry_reponse: self.envoyer_reponse(msg, entry), width=10)
                bouton_envoyer.grid(row=i, column=9, padx=10, pady=5)

        except mysql.connector.Error as err:
            print(f"Erreur lors de la récupération des informations de la base de données : {err}")

    def afficher_infos_utilisateurs(self):
        try:
            self.db_handler.mycursor.execute("SELECT id, nom_et_prenom, email FROM utilisateurs")
            utilisateurs_infos = self.db_handler.mycursor.fetchall()

            utilisateurs_frame = tk.Frame(self.root, bg="white", borderwidth=5, relief=tk.RIDGE, padx=20, pady=20, bd=10, highlightbackground="black")
            utilisateurs_frame.pack(expand=True)

            # Affichage des utilisateurs
            for i, utilisateur_info in enumerate(utilisateurs_infos):
                label_id = tk.Label(utilisateurs_frame, text=f"ID:", bg='white')
                label_id.grid(row=i, column=0, sticky="w")

                id_content = tk.Label(utilisateurs_frame, text=utilisateur_info[0], bg='white')
                id_content.grid(row=i, column=1, sticky="w")

                label_nom = tk.Label(utilisateurs_frame, text=f"Nom:", bg='white')
                label_nom.grid(row=i, column=2, sticky="w")

                nom_content = tk.Label(utilisateurs_frame, text=utilisateur_info[1], bg='white')
                nom_content.grid(row=i, column=3, sticky="w")

                label_email = tk.Label(utilisateurs_frame, text="Email:", bg='white')
                label_email.grid(row=i, column=4, sticky="w")

                email_content = tk.Label(utilisateurs_frame, text=utilisateur_info[2], bg='white')
                email_content.grid(row=i, column=5, sticky="w")

        except mysql.connector.Error as err:
            print(f"Erreur lors de la récupération des informations des utilisateurs : {err}")

    def afficher_infos_produits(self):
        try:
            self.db_handler.mycursor.execute("SELECT ID_PRODUIT, Nom, Prix FROM produit")
            produit_infos = self.db_handler.mycursor.fetchall()

            produits_frame = tk.Frame(self.root, bg="white", borderwidth=5, relief=tk.RIDGE, padx=20, pady=20, bd=10, highlightbackground="black")
            produits_frame.pack(expand=True)

            # Affichage des produits
            for i, produit_info in enumerate(produit_infos):
                label_id = tk.Label(produits_frame, text=f"ID:", bg='white')
                label_id.grid(row=i, column=0, sticky="w")

                id_content = tk.Label(produits_frame, text=produit_info[0], bg='white')
                id_content.grid(row=i, column=1, sticky="w")

                label_nom = tk.Label(produits_frame, text=f"Nom:", bg='white')
                label_nom.grid(row=i, column=2, sticky="w")

                nom_content = tk.Label(produits_frame, text=produit_info[1], bg='white')
                nom_content.grid(row=i, column=3, sticky="w")

                label_prix = tk.Label(produits_frame, text="Prix:", bg='white')
                label_prix.grid(row=i, column=4, sticky="w")

                prix_content = tk.Label(produits_frame, text=produit_info[2], bg='white')
                prix_content.grid(row=i, column=5, sticky="w")

        except mysql.connector.Error as err:
            print(f"Erreur lors de la récupération des informations des produits : {err}")

root = tk.Tk()
app = MyApp(root)
root.mainloop()