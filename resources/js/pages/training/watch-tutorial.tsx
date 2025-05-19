import AppLayout from '@/layouts/app-layout';
import { Head, Link, router } from '@inertiajs/react';
import React, { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Card, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { ArrowLeft } from 'lucide-react';

export default function WatchTutorial({ session, topic }) {
    const [played, setPlayed] = useState(false);
    const handleRetake = () =>
        router.get(route('training.questions.show', { session: session.id, topic: topic.id }));

    return (
        <AppLayout>
            <Head title="Dashboard" />
            <Card className="w-full max-w-5xl border-none shadow-none mx-auto">
                <CardHeader className="relative pb-2 pt-6 text-center">
                    <Link href={route('training.show', { session: session.id })}
                        className="absolute left-0 top-6 flex items-center text-sm text-muted-foreground"
                    ><ArrowLeft className="w-4 h-4 mr-2" />Go back</Link>
                    <CardTitle className="text-2xl md:text-3xl font-bold">Retaking test for topic {topic.name}</CardTitle>
                    <CardDescription className="mt-2">Please watch the following video before retaking the test.
                    </CardDescription>
                </CardHeader>
                <div className="p-4">
                    <video className="w-full h-auto rounded-xl border border-sidebar-border/70"
                           controls
                           onEnded={() => setPlayed(true)}
                    >
                        <source src={topic.video_url}
                            type="video/mp4" />Your browser does not support the video tag.</video>
                </div>
                <Button onClick={handleRetake} disabled={!played} className="mt-4 max-w-xs w-full mx-auto">
                    Retake test
                </Button>
            </Card>
        </AppLayout>
    );
}
