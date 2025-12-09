# LMS Mobile App - React Native Setup Guide

## 📱 React Native App untuk LMS Sekolah

Panduan lengkap untuk setup dan development aplikasi mobile LMS menggunakan React Native.

---

## 🚀 Quick Start

### Prerequisites
- Node.js >= 18
- npm atau yarn
- Android Studio (untuk Android)
- Xcode (untuk iOS, Mac only)
- React Native CLI

### Installation

```bash
# 1. Install React Native CLI globally
npm install -g react-native-cli

# 2. Create new React Native project
npx react-native init LMSMobile
cd LMSMobile

# 3. Install dependencies
npm install @react-navigation/native @react-navigation/stack @react-navigation/bottom-tabs
npm install react-native-screens react-native-safe-area-context
npm install axios
npm install @react-native-async-storage/async-storage
npm install react-native-vector-icons
npm install react-native-paper
npm install formik yup
npm install @react-native-community/netinfo

# 4. For iOS (Mac only)
cd ios && pod install && cd ..

# 5. Run the app
# Android
npm run android

# iOS
npm run ios
```

---

## 📁 Project Structure

```
LMSMobile/
├── android/                  # Android native code
├── ios/                      # iOS native code
├── src/
│   ├── assets/              # Images, fonts, etc
│   │   ├── images/
│   │   └── fonts/
│   ├── components/          # Reusable components
│   │   ├── common/
│   │   │   ├── Button.js
│   │   │   ├── Card.js
│   │   │   ├── Input.js
│   │   │   ├── Loading.js
│   │   │   └── Header.js
│   │   ├── StudentCard.js
│   │   ├── TeacherCard.js
│   │   └── CourseCard.js
│   ├── config/              # Configuration files
│   │   ├── api.config.js
│   │   └── colors.js
│   ├── navigation/          # Navigation setup
│   │   ├── AppNavigator.js
│   │   └── AuthNavigator.js
│   ├── screens/             # Screen components
│   │   ├── auth/
│   │   │   ├── LoginScreen.js
│   │   │   └── SplashScreen.js
│   │   ├── dashboard/
│   │   │   └── DashboardScreen.js
│   │   ├── students/
│   │   │   ├── StudentListScreen.js
│   │   │   ├── StudentDetailScreen.js
│   │   │   └── StudentFormScreen.js
│   │   ├── teachers/
│   │   │   ├── TeacherListScreen.js
│   │   │   ├── TeacherDetailScreen.js
│   │   │   └── TeacherFormScreen.js
│   │   ├── courses/
│   │   │   ├── CourseListScreen.js
│   │   │   ├── CourseDetailScreen.js
│   │   │   └── CourseFormScreen.js
│   │   └── profile/
│   │       └── ProfileScreen.js
│   ├── services/            # API services
│   │   ├── api.js
│   │   ├── authService.js
│   │   ├── studentService.js
│   │   ├── teacherService.js
│   │   ├── courseService.js
│   │   └── dashboardService.js
│   ├── store/               # State management (Context API or Redux)
│   │   ├── AuthContext.js
│   │   └── AppContext.js
│   ├── utils/               # Utility functions
│   │   ├── helpers.js
│   │   └── validators.js
│   └── App.js              # Main app component
├── .env                     # Environment variables
├── package.json
└── README.md
```

---

## 🔧 Configuration Files

### 1. API Configuration
**File:** `src/config/api.config.js`

```javascript
export const API_CONFIG = {
  // Development
  BASE_URL: __DEV__ 
    ? 'http://192.168.1.100:8000/api/v1'  // Ganti dengan IP laptop Anda
    : 'https://your-production-domain.com/api/v1',
  
  TIMEOUT: 30000,
  
  // Headers
  HEADERS: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
};
```

### 2. Colors Configuration
**File:** `src/config/colors.js`

```javascript
export const COLORS = {
  primary: '#6366F1',      // Indigo
  secondary: '#8B5CF6',    // Purple
  success: '#10B981',      // Green
  warning: '#F59E0B',      // Amber
  error: '#EF4444',        // Red
  
  background: '#F9FAFB',   // Gray 50
  surface: '#FFFFFF',
  
  text: {
    primary: '#111827',    // Gray 900
    secondary: '#6B7280',  // Gray 500
    disabled: '#9CA3AF',   // Gray 400
  },
  
  border: '#E5E7EB',       // Gray 200
};
```

### 3. Environment Variables
**File:** `.env`

```bash
API_BASE_URL=http://192.168.1.100:8000/api/v1
API_TIMEOUT=30000
```

---

## 📱 Sample Code

### 1. API Client Setup
**File:** `src/services/api.js`

```javascript
import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { API_CONFIG } from '../config/api.config';

const apiClient = axios.create({
  baseURL: API_CONFIG.BASE_URL,
  timeout: API_CONFIG.TIMEOUT,
  headers: API_CONFIG.HEADERS,
});

// Request interceptor
apiClient.interceptors.request.use(
  async (config) => {
    const token = await AsyncStorage.getItem('authToken');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Response interceptor
apiClient.interceptors.response.use(
  (response) => response,
  async (error) => {
    if (error.response?.status === 401) {
      // Token expired
      await AsyncStorage.multiRemove(['authToken', 'user']);
      // Navigate to login (implement navigation logic)
    }
    return Promise.reject(error);
  }
);

export default apiClient;
```

---

### 2. Auth Context
**File:** `src/store/AuthContext.js`

```javascript
import React, { createContext, useState, useEffect } from 'react';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { authService } from '../services/authService';

export const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [isLoading, setIsLoading] = useState(true);
  const [isSignedIn, setIsSignedIn] = useState(false);

  useEffect(() => {
    checkLoginStatus();
  }, []);

  const checkLoginStatus = async () => {
    try {
      const token = await AsyncStorage.getItem('authToken');
      const userData = await AsyncStorage.getItem('user');
      
      if (token && userData) {
        setUser(JSON.parse(userData));
        setIsSignedIn(true);
      }
    } catch (error) {
      console.error('Check login error:', error);
    } finally {
      setIsLoading(false);
    }
  };

  const login = async (email, password) => {
    try {
      const response = await authService.login(email, password);
      setUser(response.data.user);
      setIsSignedIn(true);
      return response;
    } catch (error) {
      throw error;
    }
  };

  const logout = async () => {
    try {
      await authService.logout();
      setUser(null);
      setIsSignedIn(false);
    } catch (error) {
      console.error('Logout error:', error);
    }
  };

  return (
    <AuthContext.Provider
      value={{
        user,
        isLoading,
        isSignedIn,
        login,
        logout,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
};
```

---

### 3. Login Screen
**File:** `src/screens/auth/LoginScreen.js`

```javascript
import React, { useState, useContext } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  Alert,
  ActivityIndicator,
} from 'react-native';
import { AuthContext } from '../../store/AuthContext';
import { COLORS } from '../../config/colors';

const LoginScreen = ({ navigation }) => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);
  const { login } = useContext(AuthContext);

  const handleLogin = async () => {
    if (!email || !password) {
      Alert.alert('Error', 'Email dan password harus diisi');
      return;
    }

    try {
      setLoading(true);
      await login(email, password);
      // Navigation handled by AuthContext
    } catch (error) {
      const message = error.response?.data?.message || 'Login gagal';
      Alert.alert('Error', message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <View style={styles.container}>
      <View style={styles.header}>
        <Text style={styles.title}>LMS Sekolah</Text>
        <Text style={styles.subtitle}>Sistem Manajemen Pembelajaran</Text>
      </View>

      <View style={styles.form}>
        <TextInput
          style={styles.input}
          placeholder="Email"
          value={email}
          onChangeText={setEmail}
          keyboardType="email-address"
          autoCapitalize="none"
          editable={!loading}
        />

        <TextInput
          style={styles.input}
          placeholder="Password"
          value={password}
          onChangeText={setPassword}
          secureTextEntry
          editable={!loading}
        />

        <TouchableOpacity
          style={[styles.button, loading && styles.buttonDisabled]}
          onPress={handleLogin}
          disabled={loading}
        >
          {loading ? (
            <ActivityIndicator color="#fff" />
          ) : (
            <Text style={styles.buttonText}>Masuk</Text>
          )}
        </TouchableOpacity>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: COLORS.background,
    padding: 20,
    justifyContent: 'center',
  },
  header: {
    alignItems: 'center',
    marginBottom: 40,
  },
  title: {
    fontSize: 32,
    fontWeight: 'bold',
    color: COLORS.primary,
    marginBottom: 8,
  },
  subtitle: {
    fontSize: 16,
    color: COLORS.text.secondary,
  },
  form: {
    width: '100%',
  },
  input: {
    backgroundColor: COLORS.surface,
    borderRadius: 12,
    padding: 16,
    marginBottom: 16,
    fontSize: 16,
    borderWidth: 1,
    borderColor: COLORS.border,
  },
  button: {
    backgroundColor: COLORS.primary,
    borderRadius: 12,
    padding: 16,
    alignItems: 'center',
    marginTop: 8,
  },
  buttonDisabled: {
    opacity: 0.6,
  },
  buttonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: '600',
  },
});

export default LoginScreen;
```

---

### 4. Dashboard Screen
**File:** `src/screens/dashboard/DashboardScreen.js`

```javascript
import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  ScrollView,
  StyleSheet,
  RefreshControl,
  ActivityIndicator,
} from 'react-native';
import { dashboardService } from '../../services/dashboardService';
import { COLORS } from '../../config/colors';

const DashboardScreen = () => {
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [data, setData] = useState(null);

  useEffect(() => {
    fetchDashboard();
  }, []);

  const fetchDashboard = async () => {
    try {
      const response = await dashboardService.getDashboard();
      setData(response);
    } catch (error) {
      console.error('Dashboard error:', error);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  const onRefresh = () => {
    setRefreshing(true);
    fetchDashboard();
  };

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color={COLORS.primary} />
      </View>
    );
  }

  return (
    <ScrollView
      style={styles.container}
      refreshControl={
        <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
      }
    >
      <Text style={styles.title}>Dashboard</Text>

      {/* Stats Cards */}
      <View style={styles.statsContainer}>
        <StatCard
          title="Total Siswa"
          value={data?.stats?.total_students || 0}
          color={COLORS.primary}
        />
        <StatCard
          title="Total Guru"
          value={data?.stats?.total_teachers || 0}
          color={COLORS.secondary}
        />
        <StatCard
          title="Mata Pelajaran"
          value={data?.stats?.total_courses || 0}
          color={COLORS.success}
        />
        <StatCard
          title="Tugas Pending"
          value={data?.stats?.pending_tasks || 0}
          color={COLORS.warning}
        />
      </View>

      {/* Recent Activities */}
      <View style={styles.section}>
        <Text style={styles.sectionTitle}>Aktivitas Terbaru</Text>
        {data?.recent_activities?.map((activity, index) => (
          <ActivityItem key={index} activity={activity} />
        ))}
      </View>
    </ScrollView>
  );
};

const StatCard = ({ title, value, color }) => (
  <View style={[styles.statCard, { borderLeftColor: color }]}>
    <Text style={styles.statValue}>{value}</Text>
    <Text style={styles.statTitle}>{title}</Text>
  </View>
);

const ActivityItem = ({ activity }) => (
  <View style={styles.activityItem}>
    <View style={styles.activityDot} />
    <View style={styles.activityContent}>
      <Text style={styles.activityTitle}>{activity.title}</Text>
      <Text style={styles.activityDesc}>{activity.description}</Text>
      <Text style={styles.activityTime}>{activity.time}</Text>
    </View>
  </View>
);

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: COLORS.background,
  },
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  title: {
    fontSize: 28,
    fontWeight: 'bold',
    color: COLORS.text.primary,
    padding: 20,
  },
  statsContainer: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    padding: 10,
  },
  statCard: {
    width: '47%',
    backgroundColor: COLORS.surface,
    borderRadius: 12,
    padding: 16,
    margin: 6,
    borderLeftWidth: 4,
  },
  statValue: {
    fontSize: 32,
    fontWeight: 'bold',
    color: COLORS.text.primary,
  },
  statTitle: {
    fontSize: 14,
    color: COLORS.text.secondary,
    marginTop: 4,
  },
  section: {
    padding: 20,
  },
  sectionTitle: {
    fontSize: 20,
    fontWeight: '600',
    color: COLORS.text.primary,
    marginBottom: 16,
  },
  activityItem: {
    flexDirection: 'row',
    marginBottom: 16,
  },
  activityDot: {
    width: 12,
    height: 12,
    borderRadius: 6,
    backgroundColor: COLORS.primary,
    marginRight: 12,
    marginTop: 4,
  },
  activityContent: {
    flex: 1,
  },
  activityTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: COLORS.text.primary,
  },
  activityDesc: {
    fontSize: 14,
    color: COLORS.text.secondary,
    marginTop: 2,
  },
  activityTime: {
    fontSize: 12,
    color: COLORS.text.disabled,
    marginTop: 4,
  },
});

export default DashboardScreen;
```

---

## 🔌 Connecting to Laravel Backend

### 1. Find Your Computer's IP Address

**Windows:**
```bash
ipconfig
# Look for "IPv4 Address" (e.g., 192.168.1.100)
```

**Mac/Linux:**
```bash
ifconfig
# Look for "inet" (e.g., 192.168.1.100)
```

### 2. Update API Configuration

Update `src/config/api.config.js` dengan IP Anda:
```javascript
BASE_URL: 'http://192.168.1.100:8000/api/v1'
```

### 3. Ensure Laravel Server is Running

```bash
cd C:\Users\KIM\Documents\lms-sekolah
php artisan serve --host=0.0.0.0 --port=8000
```

### 4. Allow Firewall Access (Windows)

Pastikan port 8000 terbuka di Windows Firewall.

---

## 🧪 Testing

### Test API Connection

```bash
# From mobile device/emulator, test connection:
curl http://192.168.1.100:8000/api/v1/auth/login -X POST \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@test.com","password":"password"}'
```

---

## 📦 Build untuk Production

### Android APK

```bash
cd android
./gradlew assembleRelease
# APK location: android/app/build/outputs/apk/release/app-release.apk
```

### Android AAB (untuk Google Play)

```bash
cd android
./gradlew bundleRelease
```

---

## 🚀 Next Steps

1. ✅ Setup project structure
2. ✅ Implement authentication
3. ✅ Create dashboard
4. 📝 Implement student management
5. 📝 Implement teacher management
6. 📝 Implement course management
7. 📝 Add profile screen
8. 📝 Testing & debugging
9. 📝 Build & deploy

---

## 📞 Support

Untuk pertanyaan atau bantuan, hubungi tim developer.

**Happy Coding!** 🎉
