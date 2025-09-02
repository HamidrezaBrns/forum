import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    username: string;
    email: string;

    avatar?: string;
    name?: string;
    bio?: string;
    job_title?: string;
    location?: string;
    website?: string;
    github?: string;
    twitter?: string;

    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

export interface BasePost {
    id: number;
    type: string;
    user: User;
    body: string;
    votes_count: number;
    comments_count: number;
    views_count: number;
    can: { update: boolean; delete: boolean; vote: boolean };
}

export interface Question extends BasePost {
    title: string;
    tags: string[];
    answers_count: number;
}

export interface Answer extends BasePost {
    can: BasePost['can'] & { accept: boolean };
}
