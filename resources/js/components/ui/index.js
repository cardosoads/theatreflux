/**
 * Shadcn/UI Components Export Index
 * 
 * This file centralizes all UI component exports for easy importing
 * Usage: import { Button, Input, Card } from '@/components/ui'
 */

// Base Components (will be created in Phase 2)// Componentes Base
export { default as Button } from './Button.vue';
export { default as Input } from './Input.vue';
export { default as Card } from './Card.vue';
export { default as CardHeader } from './CardHeader.vue';
export { default as CardContent } from './CardContent.vue';
export { default as CardFooter } from './CardFooter.vue';
export { default as CardTitle } from './CardTitle.vue';
export { default as CardDescription } from './CardDescription.vue';

// Form Components
export { default as Label } from './Label.vue';
// export { default as Textarea } from './Textarea.vue';
// export { default as Select } from './Select.vue';
// export { default as Checkbox } from './Checkbox.vue';
// export { default as RadioGroup } from './RadioGroup.vue';

// Display Components
export { default as Badge } from './Badge.vue';

// Layout Components
// export { default as Container } from './Container.vue';
// export { default as Grid } from './Grid.vue';
// export { default as Flex } from './Flex.vue';
// export { default as Separator } from './Separator.vue';

// Navigation Components
// export { default as Tabs } from './Tabs.vue';
// export { default as Breadcrumb } from './Breadcrumb.vue';
// export { default as Pagination } from './Pagination.vue';

// Feedback Components
// export { default as Alert } from './Alert.vue';
// export { default as Toast } from './Toast.vue';
// export { default as Dialog } from './Dialog.vue';
// export { default as Popover } from './Popover.vue';

// Data Display Components
// export { default as Table } from './Table.vue';
// export { default as Badge } from './Badge.vue';
// export { default as Avatar } from './Avatar.vue';
// export { default as Progress } from './Progress.vue';

// Utility Components
// export { default as Skeleton } from './Skeleton.vue';
// export { default as ScrollArea } from './ScrollArea.vue';
// export { default as Tooltip } from './Tooltip.vue';

// Placeholder export to prevent import errors
export const SHADCN_UI_VERSION = '1.0.0';

// TODO: Uncomment exports as components are created
// This structure follows shadcn/ui component organization