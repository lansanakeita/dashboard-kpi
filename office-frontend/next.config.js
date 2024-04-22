/** @type {import('next').NextConfig} */
const nextConfig = {
  webpack: (config, { dev, isServer }) => {
    // Seulement modifier la configuration en mode développement et non côté serveur
    if (dev && !isServer) {
      config.watchOptions = {
        poll: 1000,
      };
    }

    // Retourne toujours la configuration modifiée
    return config;
  },
}

module.exports = nextConfig
