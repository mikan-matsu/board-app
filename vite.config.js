// vite.config.js
import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
  ],
  server: {
    watch: {
      // symlink配下を監視しない（ELOOP対策）
      ignored: ['**/public/storage/**'],
    },
  },
})
