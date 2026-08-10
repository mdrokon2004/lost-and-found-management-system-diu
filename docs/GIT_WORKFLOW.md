# GitHub Workflow

```bash
git clone YOUR_REPOSITORY_URL
cd lost-and-found-management-system
git checkout develop
git pull origin develop

git checkout -b feature/rokon
git add .
git commit -m "feat: implement user authentication"
git push -u origin feature/rokon
```

Create a Pull Request from the feature branch into `develop`.

Suggested meaningful commits:
- feat: create normalized database schema
- feat: implement user authentication
- feat: add public lost item browsing
- feat: add lost and found reporting
- feat: implement claim workflow
- feat: create admin dashboard
- fix: validate image uploads
- style: polish glass UI
- docs: update project README
