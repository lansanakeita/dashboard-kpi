# Utilisez node alpine comme base car c'est une petite image node
FROM node:18-alpine

# Créez le répertoire dans lequel notre application Next.js va vivre
WORKDIR /app

# Copiez package.json et package-lock.json dans le répertoire de travail
COPY package*.json ./

# Installez les dépendances
RUN npm ci

# Copiez le reste de notre dossier Next.js dans /app
COPY . .

# Exposez le port 3000 pour accéder à l'application
EXPOSE 3000

# Lancez next dev pour démarrer en mode développement
CMD ["npm", "run", "dev"]
