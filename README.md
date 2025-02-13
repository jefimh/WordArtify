# WordArtify 🎨

WordArtify is a full-stack social media platform that combines AI image generation with community sharing features. Users can convert text prompts into AI-generated images and share them with the community, similar to a fusion of Instagram, Pinterest, and AI art platforms.

## 🌟 Features

### Image Generation & Sharing
- Text-to-image conversion using AI APIs (OpenAI DALL-E, Prodia AI)
- Real-time image generation progress tracking
- Download generated images locally
- Share generated images with custom titles to the community

### User Management
- Secure user authentication system
- Email verification (15-minute timeout)
- Password reset functionality
- Session management

### Community Features
- Interactive feed of community-shared images
- Like/dislike functionality
- Comment system
- Search functionality (by username, image title, or generation prompt)
- Save images to personal collection
- Real-time updates using AJAX

## 🛠️ Technical Stack

### Backend
- **PHP** (Object-Oriented)
- **MySQL** with foreign key relationships
- **REST APIs** integration
  - Prodia API
- **Security Features:**
  - Email verification
  - Password hashing
  - Session management
  - Secure file handling

### Frontend
- **JavaScript/jQuery**
- **AJAX** for seamless interactions
- **HTML5/CSS3**
- Responsive design
- Single-page application behavior

## 🏗️ Architecture

The application follows modern development practices:
- Object-Oriented Programming principles
- MVC architecture
- Separation of concerns (frontend/backend)
- Modular class structure
- RESTful API integration

## 📁 Project Structure
```
project/
├── pages/          # Main page files
├── ajax/           # AJAX handlers
├── css/            # Styling files
├── images/         # Static images
├── includes/       # Reusable components
│   └── classes/    # PHP classes
└── api/            # API integration
```

## 🔐 Security Features

- Email verification system
- Password hashing
- Secure session management
- Protected file uploads
- SQL injection prevention
- XSS protection

## 💡 Key Technical Implementations

1. **Real-time Updates**
   - jQuery/AJAX implementation for dynamic content loading
   - Live interaction updates (likes, comments)
   - Search functionality without page refresh

2. **Database Design**
   - Normalized schema
   - Foreign key constraints
   - Cascade deletion handling

3. **API Integration**
   - Multiple AI service integrations
   - Error handling and fallbacks
   - Progress tracking

## 📱 UI/UX Design

The interface was designed with inspiration from AI-generated mockups using Midjourney, focusing on:
- Clean, modern aesthetic
- Intuitive navigation
- Responsive layouts
- Consistent design language

## 🛠️ Development Process

The project was developed over several sprints, with continuous integration of new features and improvements:
1. Core authentication system
2. AI integration
3. Community features
4. UI/UX implementation
5. Security enhancements

## 🤝 Contributing

This project was developed as part of an educational program. While it's not currently open for contributions, feel free to fork and adapt for your own use.

## 📝 License

This project is available for educational and reference purposes.