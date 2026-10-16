import { defineConfig, devices } from '@playwright/test';

const baseURL = process.env.TEST_BASE_URL || 'http://caviarofswitzerland.local/';

export default defineConfig({
  testDir: './tests',
  fullyParallel: false,
  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 2 : 0,
  workers: process.env.CI ? 2 : 1,
  timeout: 45_000,
  expect: {
    timeout: 8_000
  },
  reporter: [
    ['list'],
    ['html', { open: 'never' }]
  ],
  use: {
    baseURL,
    trace: 'on-first-retry',
    screenshot: 'only-on-failure',
    video: 'retain-on-failure',
    actionTimeout: 12_000,
    navigationTimeout: 20_000
  },
  projects: [
    {
      name: 'desktop-chromium',
      use: {
        ...devices['Desktop Chrome'],
        viewport: { width: 1440, height: 950 }
      }
    },
    {
      name: 'mobile-chromium',
      use: {
        ...devices['Pixel 7']
      }
    },
    {
      name: 'mobile-webkit',
      testMatch: [
        '01-public-routes.spec.ts',
        '02-navigation-and-responsive.spec.ts',
        '03-commerce-flows.spec.ts',
        '06-accessibility.spec.ts'
      ],
      use: {
        ...devices['iPhone 13']
      }
    }
  ]
});
