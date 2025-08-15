Always check the official documentation of:
get-figma-data https://www.figma.com/design/ViA9iVuhRcBRoUjVLnl4JH/Venus---Dashboard-Builder-2021--Free-Version---Community-?node-id=102-1556&t=lZi8cI0jdzXYSFyD-1
get-library-docs Laravel
get-library-docs Vue.js
get-library-docs Konva.js
get-library-docs GSAP
get-library-docs Vue Draggable

before coding. See https://github.com/polotno-project/polotno-studio and similar projects for inspiration.

Use validated examples from documentation or reliable sources, and test them locally before integration.
Follow Laravel conventions, keeping controllers, models, and migrations clean and descriptive.
Organize Vue components clearly, and keep styles centralized.
Use English naming: CamelCase for variables/methods, PascalCase for components.
Implement features in small steps, testing each before moving on.
In GSAP, use timeline() and labels; in Konva.js, clear the stage before redrawing.
Run php artisan test and manual browser tests after changes.
Commit only after successful tests, with clear commit messages.
Document changes in CHANGELOG.md and maintain internal notes with examples and screenshots.